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
use Cms\Storage\LanguageMapperInterface;
use Krystal\Db\Sql\RawSqlFragment;

final class LanguageMapper extends AbstractMapper implements LanguageMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_cms_languages');
    }

    /**
     * Updates published state by its associated language id
     * 
     * @param string $id Language id
     * @param string $published Either 0 or 1
     * @return boolean
     */
    public function updatePublishedById($id, $published)
    {
        return $this->updateColumnByPk($id, 'published', $published);
    }

    /**
     * Updates an order by its associated id
     * 
     * @param string $id Language id
     * @param string $order New order
     * @return boolean
     */
    public function updateOrderById($id, $order)
    {
        return $this->updateColumnByPk($id, 'order', $order);
    }

    /**
     * Fetches language data by its associated id
     * 
     * @param string $id Language id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->findByPk($id);
    }

    /**
     * Fetches language id by its associated code
     * 
     * @param string $code
     * @return string
     */
    public function fetchIdByCode($code)
    {
        return $this->fetchOneColumn('id', 'code', $code);
    }

    /**
     * Fetches language code by its associated id
     * 
     * @param string $id
     * @return string
     */
    public function fetchCodeById($id)
    {
        return $this->fetchOneColumn('code', 'id', $id);
    }

    /**
     * Fetches language data by its associated code
     * 
     * @param string $code Language's code
     * @return array
     */
    public function fetchByCode($code)
    {
        return $this->fetchByColumn('code', $code, '*');
    }

    /**
     * Deletes a language by its associated id
     * 
     * @param string $id Language id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->deleteByPk($id);
    }

    /**
     * Count languages
     * 
     * @param boolean $published Whether to filter by published attribute
     * @return integer
     */
    public function countAll($published)
    {
        $db = $this->db->select()
                       ->count('id', 'count')
                       ->from(self::getTableName());

        if ($published === true) {
            $db->whereEquals('published', '1');
        }

        return $db->query('count');
    }

    /**
     * Fetch active language Ids
     * 
     * @return array
     */
    public function fetchActiveIds()
    {
        $db = $this->db->select($this->getPk())
                       ->from(self::getTableName())
                       ->whereEquals('published', '1');

        return $db->queryAll($this->getPk());
    }

    /**
     * Fetches all languages
     * 
     * @param boolean $published Whether to filter by published attribute
     * @return array
     */
    public function fetchAll($published)
    {
        $db = $this->db->select('*')
                       ->from(self::getTableName());

        if ($published === true) {
            $db->whereEquals('published', '1')
               ->orderBy(new RawSqlFragment(sprintf('`order`, CASE WHEN `order` = 0 THEN %s END DESC', LanguageMapper::getFullColumnName('id'))));
        } else {
            $db->orderBy('id')
               ->desc();
        }

        return $db->queryAll();
    }

    /**
     * Fetch language codes only
     * 
     * @param boolean $published Whether to fetch only published ones
     * @return array
     */
    public function fetchCodes($published)
    {
        $column = 'code';
        
        $db = $this->db->select($column)
                       ->from(self::getTableName());

        if ($published == true) {
            $db->whereEquals('published', '1');
        }

        return $db->queryAll($column);
    }

    /**
     * Adds a language
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function insert(array $input)
    {
        return $this->persist($input);
    }

    /**
     * Updates a language
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        return $this->persist($input);
    }
}
