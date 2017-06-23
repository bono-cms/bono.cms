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

    /**
     * Prepare translation
     * 
     * @param array $translation
     * @return array
     */
    private function prepareTranslation(array $translation)
    {
        // Take empty title from name
        if (empty($translation['title'])) {
            $translation['title'] = $translation['name'];
        }

        // Empty slug is taken from name
        if (empty($translation['slug'])) {
            $translation['slug'] = $translation['name'];
        }

        $translation['slug'] = TextUtils::sluggify($translation['slug']);

        $translation['id'] = (int) $translation['id'];
        $translation['lang_id'] = (int) $translation['lang_id'];
        $translation['web_page_id'] = (int) $translation['web_page_id'];

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
                          ->count('id')
                          ->from(static::getTranslationTable())
                          ->whereEquals('id', $id)
                          ->andWhereEquals('lang_id', $languageId)
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
            'lang_id' => (int) $translation['lang_id'],
            'target_id' => (int) $translation['id'],
            'module' => $module,
            'controller' => $controller,
            'slug' => $translation['slug']
        );

        // Add web page entry
        $this->db->insert(WebPageMapper::getTableName(), $webPage)
                 ->execute();

        $webPageId = $this->getLastPk(WebPageMapper::getTableName());

        $translation['web_page_id'] = (int) $webPageId;

        // Slug is not stored in translations
        unset($translation['slug']);

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
        $this->db->update(WebPageMapper::getTableName(), array('slug' => $translation['slug']))
                 ->whereEquals('id', $translation['web_page_id'])
                 ->execute();
        
        // Slug is not stored in translation table
        unset($translation['slug']);

        // Update translations
        $this->db->update(static::getTranslationTable(), $translation)
                 ->whereEquals('id', $translation['id'])
                 ->andWhereEquals('lang_id', $translation['lang_id'])
                 ->execute();

        return true;
    }

    /**
     * Insert/update a translation
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
                 ->whereEquals('id', $options['id'])
                 ->execute();

        // Process translations
        foreach ($translations as $translation) {
            $translation = $this->prepareTranslation($translation);

            // Is there a translation for current entity ID and its attached language ID?
            if ($this->translationExists($translation['id'], $translation['lang_id'])) {
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
            $translation['id'] = (int) $id;
            $translation['lang_id'] = (int) $languageId;

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
