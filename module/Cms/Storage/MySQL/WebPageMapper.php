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

use Cms\Storage\MySQL\AbstractMapper;
use Cms\Storage\WebPageMapperInterface;
use Krystal\Db\Sql\RawSqlFragment;

final class WebPageMapper extends AbstractMapper implements WebPageMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_cms_webpages');
    }

    /**
     * Find links with their corresponding names
     * 
     * @param array $target A collection of tableName => aliasName
     * @return array
     */
    public function findAllLinks(array $target)
    {
        // Default columns to be selected
        $defaults = array(
            self::getFullColumnName('id'),
            self::getFullColumnName('module')
        );

        $columns = array();

        // Append static column name
        foreach ($target as $table => $alias) {
            $columns[sprintf('%s.name', $table)] = sprintf('`%s`', $alias);
        }

        $db = $this->db->select(array_merge($defaults, $columns))
                       ->from(self::getTableName());

        // Append relations from dynamic tables
        foreach (array_keys($target) as $table) {
            $db->leftJoin($table)
               ->on()
               ->equals(sprintf('%s.web_page_id', $table), new RawSqlFragment(self::getFullColumnName('id')));
        }

        // Filter by language ID
        $db->whereEquals(self::getFullColumnName('lang_id'), $this->getLangId())
           ->andWhereIn(self::getFullColumnName('module'), array_values($target));

        return $db->queryAll();
    }

    /**
     * Checks whether slug already exists
     * 
     * @param string $slug
     * @return boolean
     */
    public function exists($slug)
    {
        $result = $this->db->select()
                           ->count('slug', 'count')
                           ->from(self::getTableName())
                           ->whereEquals('slug', $slug)
                           ->query('count');

        return intval($result) > 0;
    }

    /**
     * Fetches language id by associated web page id
     * 
     * @param string $id
     * @return string
     */
    public function fetchLangIdByWebPageId($id)
    {
        return $this->findColumnByPk($id, 'lang_id');
    }

    /**
     * Fetches web page's slug by its associated id
     * 
     * @param string $webPageId
     * @return string
     */
    public function fetchSlugByWebPageId($webPageId)
    {
        return $this->findColumnByPk($webPageId, 'slug');
    }

    /**
     * Fetches all web pages
     * 
     * @param array $excludedModules Modules to be ignored
     * @param string $langId Optional language id
     * @return array
     */
    public function fetchAll(array $excludedModules = array(), $langId = null)
    {
        if (is_null($langId)) {
            $langId = $this->getLangId();
        }

        $db = $this->db->select('*')
                       ->from(self::getTableName())
                       ->whereEquals('lang_id', $langId);

        if (!empty($excludedModules)) {
            $db->andWhereNotIn('module', $excludedModules);
        }

        return $db->queryAll();
    }

    /**
     * Updates a web page
     * 
     * @param string $id Web page identification
     * @param string $slug Web page's new slug
     * @param string $controller Optionally controller can be updated too
     * @return boolean
     */
    public function update($id, $slug, $controller = null)
    {
        $data = array(
            'id' => $id,
            'slug' => $slug
        );

        if ($controller !== null) {
            $data = array_merge($data, array('controller' => $controller));
        }

        return $this->persist($data);
    }

    /**
     * Inserts web page's data
     * 
     * @param array $data
     * @return boolean
     */
    public function insert(array $data)
    {
        return $this->persist($this->getWithLang($data));
    }

    /**
     * Fetches web page's data by associated slug
     * 
     * @param string $slug
     * @return array
     */
    public function fetchBySlug($slug)
    {
        return $this->fetchByColumn('slug', $slug);
    }

    /**
     * Fetches web page's data by target id
     * 
     * @param string $targetId
     * @return array
     */
    public function fetchSlugByTargetId($targetId)
    {
        return $this->db->select('slug')
                        ->from(static::getTableName())
                        ->whereEquals('target_id', $targetId)
                        ->query('slug');
    }

    /**
     * Fetches web page's data by its associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->findByPk($id);
    }

    /**
     * Deletes a web page by its associated id
     * 
     * @param string $id Web page id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->deleteByPk($id);
    }
}
