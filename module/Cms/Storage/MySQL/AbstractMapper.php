<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Storage\MySQL;

use RuntimeException;
use Krystal\Db\Sql\AbstractMapper as BaseMapper;
use Krystal\Db\Sql\RawSqlFragment;
use Krystal\Text\SlugGenerator;
use Krystal\Text\TextUtils;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Date\TimeHelper;

abstract class AbstractMapper extends BaseMapper
{
    /**
     * Language identification
     * 
     * @var string
     */
    protected $language;

    /* Shared column names */
    const PARAM_COLUMN_ID = 'id';
    const PARAM_COLUMN_LANG_ID = 'lang_id';
    const PARAM_COLUMN_WEB_PAGE_ID = 'web_page_id';
    const PARAM_COLUMN_TARGET_ID = 'target_id';
    const PARAM_COLUMN_TITLE = 'title';
    const PARAM_COLUMN_NAME = 'name';
    const PARAM_COLUMN_SLUG = 'slug';
    const PARAM_COLUMN_CONTROLLER = 'controller';
    const PARAM_COLUMN_MODULE = 'module';
    const PARAM_COLUMN_LASTMOD = 'lastmod';
    const PARAM_COLUMN_CHANGEFREQ = 'changefreq';
    const PARAM_COLUMN_PRIORITY = 'priority';

    /**
     * Fetches web page's slug by its associated id
     * 
     * @param string $webPageId
     * @return string
     */
    private function fetchSlugByWebPageId($webPageId)
    {
        return $this->db->select(self::PARAM_COLUMN_SLUG)
                        ->from(WebPageMapper::getTableName())
                        ->whereEquals(self::PARAM_COLUMN_ID, $webPageId)
                        ->queryScalar();
    }

    /**
     * Checks whether slug already exists
     * 
     * @param string $slug
     * @return boolean
     */
    public function slugExists($slug)
    {
        $result = $this->db->select()
                           ->count(self::PARAM_COLUMN_SLUG)
                           ->from(WebPageMapper::getTableName())
                           ->whereEquals(self::PARAM_COLUMN_SLUG, $slug)
                           ->queryScalar();

        return intval($result) > 0;
    }

    /**
     * Returns unique slug
     * 
     * @param string $slug
     * @return string
     */
    private function getUniqueSlug($slug)
    {
        $generator = new SlugGenerator;
        return $generator->getUniqueSlug(array($this, 'slugExists'), $slug);
    }

    /**
     * Saves the entity
     * 
     * @param array $options
     * @param array $translations
     * @return boolean
     */
    final public function saveEntity(array $options, array $translations)
    {
        if (!empty($options[self::PARAM_COLUMN_ID])) {
            $id = (int) $options[self::PARAM_COLUMN_ID];

            // Update entity
            $this->db->update(static::getTableName(), $options)
                     ->whereEquals(self::PARAM_COLUMN_ID, $options[self::PARAM_COLUMN_ID])
                     ->execute();
        } else {
            // ID is incremented automatically, so no need to insert it
            unset($options[self::PARAM_COLUMN_ID]);

            // Add entity configuration
            $this->db->insert(static::getTableName(), $options)
                     ->execute();

            // Last entity ID
            $id = (int) $this->getLastId();
        }

        // Now handle translations
        foreach ($translations as $translation) {
            // Safe type casting
            $translation[self::PARAM_COLUMN_ID] = $id;
            $translation[self::PARAM_COLUMN_LANG_ID] = (int) $translation[self::PARAM_COLUMN_LANG_ID];

            if ($this->translationExists($translation[self::PARAM_COLUMN_ID], $translation[self::PARAM_COLUMN_LANG_ID])) {
                // Update translations
                $this->db->update(static::getTranslationTable(), $translation)
                         ->whereEquals(self::PARAM_COLUMN_ID, $translation[self::PARAM_COLUMN_ID])
                         ->andWhereEquals(self::PARAM_COLUMN_LANG_ID, (int) $translation[self::PARAM_COLUMN_LANG_ID])
                         ->execute();
            } else {
                // Insert translation
                $this->db->insert(static::getTranslationTable(), $translation)
                         ->execute();
            }
        }

        return true;
    }

    /**
     * Create entity select
     * 
     * @param array $columns Columns to be selected
     * @param string $table Table name in case needs to be overridden
     * @param array $extra Extra condition when joining translation table
     * @return \Krystal\Db\Db
     */
    final protected function createEntitySelect(array $columns, $table = null, array $extraCondition = array())
    {
        // By default, handle current table
        if ($table === null) {
            $table = static::getTableName();
        }

        // Merge main condition with extra one
        $condition = array_merge(array(
            static::column(self::PARAM_COLUMN_ID) => new RawSqlFragment(static::column(self::PARAM_COLUMN_ID, static::getTranslationTable()))
        ), $extraCondition);

        $db = $this->db->select($columns, true)
                       ->from($table)
                       // Translation relation
                       ->leftJoin(static::getTranslationTable(), $condition);

        return $db;
    }

    /**
     * Finds an entity
     * 
     * @param array $columns Columns to be selected
     * @param string $id Entity ID
     * @param boolean $withTranslations Whether to fetch all translations or not
     * @return array
     */
    final protected function findEntity(array $columns, $id, $withTranslations)
    {
        $db = $this->createEntitySelect($columns)
                   ->whereEquals(self::column(self::PARAM_COLUMN_ID), $id);

        if ($withTranslations === true) {
            return $db->queryAll();
        } else {
            return $db->andWhereEquals(self::column(self::PARAM_COLUMN_LANG_ID, static::getTranslationTable()), $this->getLangId())
                      ->query();
        }
    }

    /**
     * Find many entities
     * 
     * @param array $columns Columns to be selected
     * @param array $ids Entity IDs
     * @return array
     */
    final protected function findEntities(array $columns, array $ids)
    {
        $db = $this->createEntitySelect($columns)
                   ->whereIn(self::column(self::PARAM_COLUMN_ID), $ids)
                   ->andWhereEquals(self::column(self::PARAM_COLUMN_LANG_ID, static::getTranslationTable()), $this->getLangId());

        return $db->queryAll();
    }

    /**
     * Delete an entity completely
     * 
     * @param string|array $id
     * @return boolean
     */
    final public function deleteEntity($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        // Prevent removing all, if no keys specified
        if (empty($id)) {
            return false;
        }

        $tables = array(
            static::getTableName(),
            static::getTranslationTable()
        );

        // Delete entity with all its relational data
        $db = $this->db->delete($tables)
                       ->from(static::getTableName())
                       // Translation relation
                       ->leftJoin(static::getTranslationTable(), array(
                           static::column(self::PARAM_COLUMN_ID) => new RawSqlFragment(static::column(self::PARAM_COLUMN_ID, static::getTranslationTable()))
                       ))
                       // Current ID
                      ->whereIn(static::column(self::PARAM_COLUMN_ID), $id);

        return $db->execute();
    }

    /**
     * Find many web pages at once (by entity ids)
     * 
     * @param array $columns Columns to be selected
     * @param string $id Entity IDs
     * @return array
     */
    final protected function findWebPages(array $columns, $ids)
    {
        $db = $this->createWebPageSelect($columns)
                   ->whereIn(self::column(self::PARAM_COLUMN_ID, static::getTableName()), $ids)
                   ->andWhereEquals(self::column(self::PARAM_COLUMN_LANG_ID, static::getTranslationTable()), $this->getLangId());

        return $db->queryAll();
    }

    /**
     * Finds a web page
     * 
     * @param array $columns Columns to be selected
     * @param string $id Entity ID
     * @param boolean $withTranslations Whether to fetch all translations or not
     * @return array
     */
    final protected function findWebPage(array $columns, $id, $withTranslations)
    {
        $db = $this->createWebPageSelect($columns)
                   ->whereEquals(self::column(self::PARAM_COLUMN_ID), $id);

        if ($withTranslations === true) {
            return $db->queryAll();
        } else {
            return $db->andWhereEquals(self::column(self::PARAM_COLUMN_LANG_ID, static::getTranslationTable()), $this->getLangId())
                      ->query();
        }
    }

    /**
     * Creates shared web page select
     * 
     * @param array $columns Columns to be selected
     * @param string $table Table name in case needs to be overridden
     * @return \Krystal\Db\Db
     */
    final protected function createWebPageSelect(array $columns, $table = null)
    {
        if ($table === null) {
            $table = static::getTableName();
        }

        return $this->createEntitySelect($columns, $table)
                    // Web page relation
                    ->leftJoin(WebPageMapper::getTableName(), array(
                        WebPageMapper::column(self::PARAM_COLUMN_ID) => new RawSqlFragment(static::column(self::PARAM_COLUMN_WEB_PAGE_ID, static::getTranslationTable())),
                        WebPageMapper::column(self::PARAM_COLUMN_LANG_ID) => new RawSqlFragment(static::column(self::PARAM_COLUMN_LANG_ID, static::getTranslationTable()))
                    ));
    }

    /**
     * Find switching URLs
     * 
     * @param string $id Entity ID
     * @param string $module
     * @param string $controller
     * @return array
     */
    final public function createSwitchUrls($id, $module, $controller)
    {
        $output = array();

        $urls  = $this->findSwitchUrls($id, $module, $controller);
        $count = count($urls);

        foreach ($urls as $url) {
            if ($count > 1) {
                $switchUrl = sprintf('/%s/%s/', $url['code'], $url['slug']);
            } else {
                $switchUrl = sprintf('/%s/', $url['slug']);
            }

            $entity = new VirtualEntity();
            $entity->setName($url['name'])
                   ->setCode($url['code'])
                   ->setFlag($url['flag'])
                   ->setSwitchUrl($switchUrl)
                   ->setActive($this->getLangId() == $url[self::PARAM_COLUMN_ID]);

            $output[] = $entity;
        }

        return $output;
    }

    /**
     * Find switching URLs
     * 
     * @param string $id Entity ID
     * @param string $module
     * @param string $controller
     * @return array
     */
    private function findSwitchUrls($id, $module, $controller)
    {
        // Columns to be selected
        $columns = array(
            LanguageMapper::column(self::PARAM_COLUMN_ID),
            LanguageMapper::column(self::PARAM_COLUMN_NAME),
            LanguageMapper::column('code'),
            LanguageMapper::column('flag'),
            WebPageMapper::column(self::PARAM_COLUMN_SLUG)
        );

        return $this->db->select($columns)
                        ->from(WebPageMapper::getTableName())
                        ->innerJoin(LanguageMapper::getTableName(), array(
                            LanguageMapper::column(self::PARAM_COLUMN_ID) => new RawSqlFragment(WebPageMapper::column(self::PARAM_COLUMN_LANG_ID))
                        ))
                        // Filter by these constraints
                        ->whereEquals(self::PARAM_COLUMN_TARGET_ID, $id)
                        ->andWhereEquals(self::PARAM_COLUMN_MODULE, $module)
                        ->andWhereEquals(self::PARAM_COLUMN_CONTROLLER, $controller)
                        ->andWhereEquals(LanguageMapper::column('published'), new RawSqlFragment('1'))
						->orderBy(new RawSqlFragment(sprintf('`order`, CASE WHEN `order` = 0 THEN %s END DESC', LanguageMapper::column('id'))))
                        ->queryAll();
    }

    /**
     * Prepare translation before inserting or updating
     * 
     * @param array $translation
     * @return array
     */
    private function prepareTranslation(array $translation)
    {
        // Take empty title from name
        if (empty($translation[self::PARAM_COLUMN_TITLE])) {
            $translation[self::PARAM_COLUMN_TITLE] = $translation[self::PARAM_COLUMN_NAME];
        }

        // Empty slug is taken from name
        if (empty($translation[self::PARAM_COLUMN_SLUG])) {
            $translation[self::PARAM_COLUMN_SLUG] = $translation[self::PARAM_COLUMN_NAME];

            // Another chance to generate a slug is to take from a title
            if (empty($translation[self::PARAM_COLUMN_NAME])) {
                $translation[self::PARAM_COLUMN_NAME] = $translation[self::PARAM_COLUMN_TITLE];
                $translation[self::PARAM_COLUMN_SLUG] = $translation[self::PARAM_COLUMN_TITLE];
            }
        }

        $translation[self::PARAM_COLUMN_SLUG] = TextUtils::sluggify($translation[self::PARAM_COLUMN_SLUG]);

        // Safe type casting
        $translation[self::PARAM_COLUMN_ID] = (int) $translation[self::PARAM_COLUMN_ID];
        $translation[self::PARAM_COLUMN_LANG_ID] = (int) $translation[self::PARAM_COLUMN_LANG_ID];
        $translation[self::PARAM_COLUMN_WEB_PAGE_ID] = (int) $translation[self::PARAM_COLUMN_WEB_PAGE_ID];

        return $translation;
    }

    /**
     * Checks whether there's attached language ID for particular entity
     * 
     * @param string $id Entity ID
     * @param string $languageId Language ID
     * @return boolean
     */
    private function translationExists($id, $languageId)
    {
        $count = $this->db->select()
                          ->count(self::PARAM_COLUMN_ID)
                          ->from(static::getTranslationTable())
                          ->whereEquals(self::PARAM_COLUMN_ID, $id)
                          ->andWhereEquals(self::PARAM_COLUMN_LANG_ID, $languageId)
                          ->queryScalar();

        return intval($count) > 0;
    }

    /**
     * Insert entity translation
     * 
     * @param string $module Module name
     * @param string $controller Framework-compliant controller name
     * @param array $translation Entity translation
     * @return boolean
     */
    private function insertTranslation($module, $controller, array $translation)
    {
        // Ensure the slug is unique
        $translation[self::PARAM_COLUMN_SLUG] = $this->getUniqueSlug($translation[self::PARAM_COLUMN_SLUG]);

        // Web page data
        $webPage = array(
            self::PARAM_COLUMN_LANG_ID => (int) $translation[self::PARAM_COLUMN_LANG_ID],
            self::PARAM_COLUMN_TARGET_ID => (int) $translation[self::PARAM_COLUMN_ID],
            self::PARAM_COLUMN_MODULE => $module,
            self::PARAM_COLUMN_CONTROLLER => $controller,
            self::PARAM_COLUMN_SLUG => $translation[self::PARAM_COLUMN_SLUG],
            self::PARAM_COLUMN_LASTMOD => TimeHelper::getNow(),
            self::PARAM_COLUMN_CHANGEFREQ => $translation[self::PARAM_COLUMN_CHANGEFREQ],
            self::PARAM_COLUMN_PRIORITY => $translation[self::PARAM_COLUMN_PRIORITY]
        );

        // Add web page entry
        $this->db->insert(WebPageMapper::getTableName(), $webPage)
                 ->execute();

        // Append a web page
        $translation[self::PARAM_COLUMN_WEB_PAGE_ID] = (int) $this->getLastPk(WebPageMapper::getTableName());

        // Sitemap attributes are not stored in translation table
        $translation = $this->removeSitemapParams($translation);

        // Add entity translation
        $this->db->insert(static::getTranslationTable(), $translation)
                 ->execute();

        return true;
    }

    /**
     * Update translation data
     * 
     * @param array $translation
     * @param string $controller Optional controller to be overridden
     * @return boolean
     */
    private function updateTranslation(array $translation, $controller = null)
    {
        // Before adding a new slug, make sure it has been changed
        if ($this->fetchSlugByWebPageId($translation[self::PARAM_COLUMN_WEB_PAGE_ID]) !== $translation[self::PARAM_COLUMN_SLUG]) {
            $translation[self::PARAM_COLUMN_SLUG] = $this->getUniqueSlug($translation[self::PARAM_COLUMN_SLUG]);
        }

        // Web page data to be updated
        $webPage = array(
            self::PARAM_COLUMN_SLUG => $translation[self::PARAM_COLUMN_SLUG],
            self::PARAM_COLUMN_LASTMOD => TimeHelper::getNow(),
            self::PARAM_COLUMN_CHANGEFREQ => $translation[self::PARAM_COLUMN_CHANGEFREQ],
            self::PARAM_COLUMN_PRIORITY => $translation[self::PARAM_COLUMN_PRIORITY]
        );

        // Update controller if explicit provided
        if ($controller !== null) {
            $webPage[self::PARAM_COLUMN_CONTROLLER] = $controller;
        }

        // Update web page
        $this->db->update(WebPageMapper::getTableName(), $webPage)
                 ->whereEquals(self::PARAM_COLUMN_ID, $translation[self::PARAM_COLUMN_WEB_PAGE_ID])
                 ->execute();

        // Sitemap attributes are not stored in translation table
        $translation = $this->removeSitemapParams($translation);

        // Update translations
        $this->db->update(static::getTranslationTable(), $translation)
                 ->whereEquals(self::PARAM_COLUMN_ID, $translation[self::PARAM_COLUMN_ID])
                 ->andWhereEquals(self::PARAM_COLUMN_LANG_ID, (int) $translation[self::PARAM_COLUMN_LANG_ID])
                 ->execute();

        return true;
    }

    /**
     * Remove sitemap parameters from translation data
     * 
     * @param array $translation
     * @return array
     */
    private function removeSitemapParams(array $translation)
    {
        unset(
            $translation[self::PARAM_COLUMN_SLUG],
            $translation[self::PARAM_COLUMN_CHANGEFREQ],
            $translation[self::PARAM_COLUMN_PRIORITY]
        );

        return $translation;
    }

    /**
     * Delete a page or entity completely
     * 
     * @param string|array $id
     * @return boolean
     */
    final public function deletePage($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        // Prevent removing all, if no keys specified
        if (empty($id)) {
            return false;
        }

        $tables = array(
            static::getTableName(),
            static::getTranslationTable(),
            WebPageMapper::getTableName()
        );

        // Delete entity with all its relational data
        return $this->db->delete($tables)
                     ->from(static::getTableName())
                     // Translation relation
                     ->innerJoin(static::getTranslationTable(), array(
                        static::column(self::PARAM_COLUMN_ID) => new RawSqlFragment(static::column(self::PARAM_COLUMN_ID, static::getTranslationTable()))
                     ))
                     // Web page relation (optional)
                     ->leftJoin(WebPageMapper::getTableName(), array(
                        WebPageMapper::column(self::PARAM_COLUMN_ID) => new RawSqlFragment(static::column(self::PARAM_COLUMN_WEB_PAGE_ID, static::getTranslationTable()))
                     ))
                     // Current ID
                     ->whereIn(static::column(self::PARAM_COLUMN_ID), $id)
                     ->execute();
    }

    /**
     * Inserts or updates page
     * 
     * @param string $module Module name
     * @param string $controller Framework-compliant controller name
     * @param array $options Entity options
     * @param array $translation Entity translations
     * @return boolean
     */
    final public function savePage($module, $controller, array $options, array $translations)
    {
        if (!empty($options[self::PARAM_COLUMN_ID])) {
            return $this->updatePage($module, $controller, $options, $translations);
        } else {
            // ID is incremented automatically, so no need to insert it
            unset($options[self::PARAM_COLUMN_ID]);
            return $this->insertPage($module, $controller, $options, $translations);
        }
    }

    /**
     * Updates a page with translations and slug
     * 
     * @param string $module Module name
     * @param string $controller Framework-compliant controller name
     * @param array $options Entity options
     * @param array $translation Entity translations
     * @return boolean
     */
    final public function updatePage($module, $controller, array $options, array $translations)
    {
        // Update entity
        $this->db->update(static::getTableName(), $options)
                 ->whereEquals(self::PARAM_COLUMN_ID, $options[self::PARAM_COLUMN_ID])
                 ->execute();

        // Process translations
        foreach ($translations as $translation) {
            $translation = $this->prepareTranslation($translation);

            // Is there a translation for current entity ID and its attached language ID?
            if ($this->translationExists($translation[self::PARAM_COLUMN_ID], $translation[self::PARAM_COLUMN_LANG_ID])) {
                // If exists, then update it
                $this->updateTranslation($translation, $controller);
            } else {
                // Otherwise just insert a new row
                $this->insertTranslation($module, $controller, $translation);
            }
        }

        return true;
    }

    /**
     * Inserts a page with translations and slug
     * 
     * @param string $module Module name
     * @param string $controller Framework-compliant controller name
     * @param array $options Entity options
     * @param array $translations Entity translations
     * @return boolean
     */
    final public function insertPage($module, $controller, array $options, array $translations)
    {
        // Add entity configuration
        $this->db->insert(static::getTableName(), $options)->execute();

        // Get last inserted ID
        $id = $this->getLastId();

        // Insert web pages
        foreach ($translations as $languageId => $translation) {
            // Prepare translation
            $translation = $this->prepareTranslation($translation);

            // Append relational keys
            $translation[self::PARAM_COLUMN_ID] = (int) $id;
            $translation[self::PARAM_COLUMN_LANG_ID] = (int) $languageId;

            $this->insertTranslation($module, $controller, $translation);
        }

        return true;
    }

    /**
     * Merges target array with language id
     * 
     * @param array $data Target data
     * @return array
     */
    final protected function getWithLang(array $data)
    {
        return array_merge(array('lang_id' => $this->getLangId()), $data);
    }

    /**
     * Returns PK name
     * Most tables share the same
     * 
     * @return string
     */
    protected function getPk()
    {
        return 'id';
    }

    /**
     * Sets language id belonging to a mapper
     * 
     * @return \Cms\Storage\MySQL\AbstractMapper
     */
    final public function setLangId($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Returns last id
     * 
     * @throws \RuntimeException If no language key provided
     * @return string
     */
    final public function getLangId()
    {
        if (!$this->language) {
            throw new RuntimeException('Language key was not defined');
        }

        return $this->language;
    }

    /**
     * Returns last id from a table
     * 
     * @return integer
     */
    final public function getLastId()
    {
        return $this->getLastPk();
    }

    /**
     * Fetches web page id by provided target id
     * 
     * @param string $id
     * @return integer
     */
    final public function fetchWebPageIdById($id)
    {
        return $this->findColumnByPk($id, 'web_page_id');
    }

    /**
     * Fetches web page title by its associated id
     * 
     * @param string $webPageId Target web page id
     * @return string
     */
    final public function fetchNameByWebPageId($webPageId)
    {
        return $this->fetchOneColumn('name', 'web_page_id', $webPageId);
    }

    /**
     * Updates web page with a new one
     * 
     * @param string $id Target id
     * @param string $webPageId Web page id
     * @return boolean
     */
    final public function updateWebPageIdById($id, $webPageId)
    {
        return $this->updateColumnByPk($id, 'web_page_id', $webPageId);
    }
}
