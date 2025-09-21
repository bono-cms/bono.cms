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

use Krystal\Application\Module\ModuleManagerInterface;

interface WebPageManagerInterface
{
    /**
     * Creates URL by target ID and module name
     * 
     * @param string $targetId
     * @param string $module
     * @param array $vars Optional query string variables
     * @return string
     */
    public function createUrl($targetId, $module, array $vars = array());

    /**
     * Extract links from registered namespaces
     * 
     * @param array $namespaces
     * @return array
     */
    public function createPrettyLinks(array $namespaces);

    /**
     * An alias for self::getUrlByWebPageId()
     * 
     * @param string $webPageId
     * @return string
     */
    public function generate($webPageId);

    /**
     * Generates URL by web page id
     * 
     * @param string $webPageId
     * @return string|boolean false on failure
     */
    public function getUrlByWebPageId($webPageId);
    
    /**
     * Builds URL by provided web page and language ids
     * 
     * @param string $webPageId
     * @param string $langId
     * @return string
     */
    public function getUrl($webPageId, $langId);
    
    /**
     * Fetches associated slug by web page id
     * 
     * @param string $webPageId
     * @return string
     */
    public function fetchSlugByWebPageId($webPageId);

    /**
     * Fetches all URLs
     * 
     * @param string $language Current language code
     * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManager
     * @return array
     */
    public function fetchURLs($locale, ModuleManagerInterface $moduleManager);

    /**
     * Surrounds a slug using provided language id to generate a language code if needed
     * 
     * @param string $slug
     * @param string $langId
     * @return string
     */
    public function surround($slug, $langId);

    /**
     * Sluggifies a string
     * 
     * @param string $raw
     * @return string
     */
    public function sluggify($raw);

    /**
     * Returns last inserted web page id
     * 
     * @return integer
     */
    public function getLastId();

    /**
     * Fetches web page's data by associated slug
     * 
     * @param string $slug
     * @param string $code Optional language code
     * @return array
     */
    public function fetchBySlug($slug, $code = null);

    /**
     * Fetches web page data its associated id
     * 
     * @param string $id Web page id
     * @return array
     */
    public function fetchById($id);

    /**
     * Fetches slug by target id (Target id is id which supplies to framework controllers by dispatcher)
     * 
     * @param string $id Target id
     * @return array
     */
    public function fetchSlugByTargetId($id);

    /**
     * Deletes web page data by its associated id
     * 
     * @param string $id Web page id
     * @param object $childMapper If any
     * @return boolean
     */
    public function deleteById($id, $childMapper = null);

    /**
     * Finds web page PKs by associated module
     * 
     * @param string $module Module name or module name with description
     * @return array
     */
    public function findPksByModule($module);

    /**
     * Remove PKs by module
     * 
     * @param string $module
     * @return boolean
     */
    public function removePksByModule($module);

    /**
     * Adds a web page
     * 
     * @param string $targetId Data to be supplied to controller
     * @param string $slug Web page slug
     * @param string $controller Web page framework's compliant controller
     * @param $childMapper Child mapper which is related to the web page being added
     * @return boolean
     */
    public function add($targetId, $slug, $module, $controller, $childMapper);

    /**
     * Updates a web page
     * 
     * @param string $id Web page id
     * @param string $slug New web page slug
     * @param string $controller Optionally a controller can be updated as well
     * @return boolean
     */
    public function update($id, $slug, $controller = null);
}
