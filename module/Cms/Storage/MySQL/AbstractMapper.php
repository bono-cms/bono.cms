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

use Krystal\Db\Sql\AbstractMapper as BaseMapper;
use Krystal\Text\TextUtils;

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
        // Web page data
        $webPage = array(
            self::PARAM_COLUMN_LANG_ID => (int) $translation[self::PARAM_COLUMN_LANG_ID],
            self::PARAM_COLUMN_TARGET_ID => (int) $translation[self::PARAM_COLUMN_ID],
            self::PARAM_COLUMN_MODULE => $module,
            self::PARAM_COLUMN_CONTROLLER => $controller,
            self::PARAM_COLUMN_SLUG => $translation[self::PARAM_COLUMN_SLUG]
        );

        // Add web page entry
        $this->db->insert(WebPageMapper::getTableName(), $webPage)
                 ->execute();

        // Append a web page
        $translation[self::PARAM_COLUMN_WEB_PAGE_ID] = (int) $this->getLastPk(WebPageMapper::getTableName());

        // Slug is not stored in translations
        unset($translation[self::PARAM_COLUMN_SLUG]);

        // Add entity translation
        $this->db->insert(static::getTranslationTable(), $translation)
                 ->execute();

        return true;
    }

    /**
     * Update translation data
     * 
     * @param array $translation
     * @return boolean
     */
    private function updateTranslation(array $translation)
    {
        // Update web page
        $this->db->update(WebPageMapper::getTableName(), array(self::PARAM_COLUMN_SLUG => $translation[self::PARAM_COLUMN_SLUG]))
                 ->whereEquals(self::PARAM_COLUMN_ID, $translation[self::PARAM_COLUMN_WEB_PAGE_ID])
                 ->execute();

        // Slug is not stored in translation table
        unset($translation[self::PARAM_COLUMN_SLUG]);

        // Update translations
        $this->db->update(static::getTranslationTable(), $translation)
                 ->whereEquals(self::PARAM_COLUMN_ID, $translation[self::PARAM_COLUMN_ID])
                 ->andWhereEquals(self::PARAM_COLUMN_LANG_ID, $translation[self::PARAM_COLUMN_LANG_ID])
                 ->execute();

        return true;
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
                $this->updateTranslation($translation);
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
     * @return string
     */
    final public function getLangId()
    {
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
