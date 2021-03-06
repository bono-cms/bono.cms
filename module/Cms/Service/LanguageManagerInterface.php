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
     * Count languages
     * 
     * @param boolean $published Whether to filter by published attribute
     * @return integer
     */
    public function getCount($published);

    /**
     * Fetches language id by its associated code
     * 
     * @param string $code
     * @return string
     */
    public function fetchIdByCode($code);

    /**
     * Changes site language
     * 
     * @param string $code Language code
     * @return boolean Depending on success
     */
    public function changeSiteLanguage($code);

    /**
     * Returns interface language code
     * 
     * @return string
     */
    public function getInterfaceLangCode();

    /**
     * Defines interface language
     * 
     * @param string $code New interface's language code
     * @return \Cms\Service\LanguageManager
     */
    public function setInterfaceLangCode($code);

    /**
     * Defines current language id
     * 
     * @param string $id Current language id to be set
     * @return \Cms\Service\LanguageManager
     */
    public function setCurrentId($id);

    /**
     * Returns current language code
     * 
     * @return string
     */
    public function getCurrentCode();

    /**
     * Returns current language id
     * 
     * @return string
     */
    public function getCurrentId();

    /**
     * Returns default language code
     * 
     * @return string
     */
    public function getDefaultCode();

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
     * Fetch active language Ids
     * 
     * @return array
     */
    public function fetchActiveIds();

    /**
     * Fetches language's entity by its associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id);

    /**
     * Fetches language bag by its associated code
     * 
     * @param string $code
     * @return \Krystal\Stdlib\VirtualEntity
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
     * Fetch language codes only
     * 
     * @param boolean $published Whether to fetch only published ones
     * @return array
     */
    public function fetchCodes($published);

    /**
     * Deletes a language by its associated id
     * 
     * @param string $id
     * @return void
     */
    public function deleteById($id);
}
