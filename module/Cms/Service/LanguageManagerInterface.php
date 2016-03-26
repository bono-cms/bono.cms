<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

/* API for Language Manager */
interface LanguageManagerInterface
{
    /**
     * Fetches language id by its associated code
     * 
     * @param string $code
     * @return string
     */
    public function fetchIdByCode($code);

    /**
     * Defines current language id
     * 
     * @param string $id Current language id to be set
     * @return boolean
     */
    public function setCurrentId($id);

    /**
     * Returns current language id
     * 
     * @return string
     */
    public function getCurrentId();

    /**
     * Fetches a language bag by current id
     * 
     * @return object|boolean
     */
    public function fetchByCurrentId();

    /**
     * Marks language id as a default one
     * 
     * @param string $id Language id to be marked as default
     * @return boolean
     */
    public function makeDefault($id);

    /**
     * Returns language default id
     * 
     * @return integer
     */
    public function getDefaultId();

    /**
     * Checks whether a language id is default
     * 
     * @param string $id
     * @return boolean
     */
    public function isDefault($id);

    /**
     * Checks whether we have a default language id
     * 
     * @return boolean
     */
    public function hasDefault();

    /**
     * Updates published values by their associated ids
     * 
     * @param array $pair
     * @return boolean Depending on success
     */
    public function updatePublished(array $pair);

    /**
     * Update orders by their associated ids
     * 
     * @param array $pair
     * @return boolean
     */
    public function updateOrders(array $pair);

    /**
     * Return all available countries in ISO3166-compliant
     * 
     * @return array
     */
    public function getCountries();

    /**
     * Returns last language id
     * 
     * @return integer
     */
    public function getLastId();

    /**
     * Adds a language
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input);

    /**
     * Updates a language
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input);

    /**
     * Fetches language bag by its associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id);

    /**
     * Fetches language bag by its associated code
     * 
     * @param string $code
     * @return \Krystal\Stdlib\VirtualBag
     */
    public function fetchByCode($code);

    /**
     * Fetches all language entities
     * 
     * @param boolean $published Whether to filter by published attribute
     * @return array
     */
    public function fetchAll($published);

    /**
     * Deletes a language by its associated id
     * 
     * @param string $id
     * @return void
     */
    public function deleteById($id);
}
