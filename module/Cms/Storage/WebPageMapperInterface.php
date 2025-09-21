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

interface WebPageMapperInterface
{
    /**
     * Finds slug and language ID by target ID and module name
     * 
     * @param string $targetId
     * @param string $module
     * @return string
     */
    public function findSlug($targetId, $module);

    /**
     * Find links with their corresponding names
     * 
     * @param array $target A collection of tableName => aliasName
     * @return array
     */
    public function findAllLinks(array $target);

    /**
     * Checks whether slug already exists
     * 
     * @param string $slug
     * @return boolean
     */
    public function exists($slug);

    /**
     * Fetches web page's slug by its associated id
     * 
     * @param string $webPageId
     * @return string
     */
    public function fetchSlugByWebPageId($webPageId);

    /**
     * Fetches all web pages
     * 
     * @param array $excludedModules Modules to be ignored
     * @param string $langId Optional language id
     * @return array
     */
    public function fetchAll(array $excludedModules = array(), $langId = null);

    /**
     * Updates a web page
     * 
     * @param string $id Web page identification
     * @param string $slug Web page's new slug
     * @param string $controller Optionally controller can be updated too
     * @return boolean
     */
    public function update($id, $slug, $controller = null);

    /**
     * Inserts web page's data
     * 
     * @param array $data
     * @return boolean
     */
    public function insert(array $data);

    /**
     * Fetches web page's data by associated slug
     * 
     * @param string $slug
     * @param string $code Optional language code
     * @return array
     */
    public function fetchBySlug($slug, $code = null);

    /**
     * Fetches web page's data by target id
     * 
     * @param string $targetId
     * @return array
     */
    public function fetchSlugByTargetId($targetId);

    /**
     * Fetches web page's data by its associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id);

    /**
     * Deletes a web page by its associated id
     * 
     * @param string $id Web page id
     * @return boolean
     */
    public function deleteById($id);
}
