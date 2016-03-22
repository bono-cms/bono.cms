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
     * Fetches all published languages
     * 
     * @return array
     */
    public function fetchAllPublished()
    {
        return $this->db->select('*')
                        ->from(static::getTableName())
                        ->whereEquals('published', '1')
                        ->queryAll();
    }

    /**
     * Fetches all languages
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->db->select('*')
                        ->from(static::getTableName())
                        ->orderBy('id')
                        ->desc()
                        ->queryAll();
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
