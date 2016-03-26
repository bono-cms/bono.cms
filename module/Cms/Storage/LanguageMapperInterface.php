<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Storage;

interface LanguageMapperInterface
{
    /**
     * Fetches language id by its associated code
     * 
     * @param string $code
     * @return string
     */
    public function fetchIdByCode($code);

    /**
     * Updates published state by its associated language id
     * 
     * @param string $id Language id
     * @param string $published Either 0 or 1
     * @return boolean
     */
    public function updatePublishedById($id, $published);

    /**
     * Updates an order by its associated id
     * 
     * @param string $id Language id
     * @param string $order New order
     * @return boolean
     */
    public function updateOrderById($id, $order);

    /**
     * Fetches language data by its associated id
     * 
     * @param string $id Language id
     * @return array
     */
    public function fetchById($id);

    /**
     * Fetches language data by its associated code
     * 
     * @param string $code Language's code
     * @return array
     */
    public function fetchByCode($code);

    /**
     * Deletes a language by its associated id
     * 
     * @param string $id Language id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Count languages
     * 
     * @param boolean $published Whether to filter by published attribute
     * @return integer
     */
    public function countAll($published);

    /**
     * Fetches all language entities
     * 
     * @param boolean $published Whether to filter by published attribute
     * @return array
     */
    public function fetchAll($published);

    /**
     * Inserts language data
     * 
     * @param array $data Language data
     * @return boolean
     */
    public function insert(array $data);

    /**
     * Updates a language
     * 
     * @param array $data Language data
     * @return boolean
     */
    public function update(array $data);
}
