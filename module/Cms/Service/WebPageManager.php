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

use Cms\Storage\WebPageMapperInterface;
use Cms\Storage\LanguageMapperInterface;
use Krystal\Text\SlugGenerator;
use Krystal\Application\Module\ModuleManagerInterface;
use Krystal\Stdlib\ArrayUtils;

final class WebPageManager extends AbstractManager implements WebPageManagerInterface
{
    /**
     * Any compliant language mapper
     * 
     * @var \Cms\Storage\LanguageMapperInterface
     */
    private $languageMapper;

    /**
     * Any compliant web page mapper
     * 
     * @var \Cms\Storage\WebPageMapperInterface
     */
    private $webPageMapper;

    /**
     * Slug generator
     * 
     * @var \Krystal\Text\SlugGenerator
     */
    private $slugGenerator;

    /**
     * Base URL to be prepended when generating URL
     * 
     * @var string
     */
    private $baseUrl;

    /**
     * State initialization
     * 
     * @param \Cms\Storage\WebPageMapperInterface $webPageMapper
     * @param \Cms\Storage\LanguageMapperInterface $languageMapper
     * @param \Krystal\Text\SlugGenerator $slugGenerator
     * @param string $baseUrl
     * @return void
     */
    public function __construct(WebPageMapperInterface $webPageMapper, LanguageMapperInterface $languageMapper, SlugGenerator $slugGenerator, $baseUrl)
    {
        $this->webPageMapper = $webPageMapper;
        $this->languageMapper = $languageMapper;
        $this->slugGenerator = $slugGenerator;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Find and process all links
     * 
     * @param array $target A collection of table names and associated module
     * @return array
     */
    public function findAllLinks(array $target)
    {
        // Visitor
        $callback = function($row){
            $output = array();

            foreach ($row as $column => $value) {
                // Append only if non-empty value found
                if (!empty($value)) {
                    // Normalize column name
                    if (!in_array($column, array('id', 'module'))) {
                        $column = 'title';
                    }

                    $output[$column] = $value;
                }
            }

            return $output;
        };

        return ArrayUtils::filterArray($this->webPageMapper->findAllLinks($target), $callback);
    }

    /**
     * An alias for self::getUrlByWebPageId()
     * 
     * @param string $webPageId
     * @return string
     */
    public function generate($webPageId)
    {
        return $this->getUrlByWebPageId($webPageId);
    }

    /**
     * Generates URL by web page id
     * 
     * @param string $webPageId
     * @return string|boolean false on failure
     */
    public function getUrlByWebPageId($webPageId)
    {
        $langId = $this->webPageMapper->fetchLangIdByWebPageId($webPageId);

        if ($langId) {
            return $this->getUrl($webPageId, $langId);
        } else {
            // Probably wrong $webPageId supplied
            return false;
        }
    }

    /**
     * Builds URL by provided web page and language ids
     * 
     * @param string $webPageId
     * @param string $langId
     * @return string
     */
    public function getUrl($webPageId, $langId)
    {
        $slug = $this->webPageMapper->fetchSlugByWebPageId($webPageId);
        return $this->surround($slug, $langId);
    }

    /**
     * Fetches associated slug by web page id
     * 
     * @param string $webPageId
     * @return string
     */
    public function fetchSlugByWebPageId($webPageId)
    {
        // Avoid querying when $webPageId is dummy
        if ($webPageId == null || $webPageId == 0) {
            return '';
        } else {
            $webPageId = (int) $webPageId;
            return $this->webPageMapper->fetchSlugByWebPageId($webPageId);
        }
    }

    /**
     * Fetches all URLs
     * 
     * @param string $base
     * @param string $language Optional language code
     * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManager
     * @return array
     */
    public function fetchURLs($base, $language, ModuleManagerInterface $moduleManager)
    {
        // Grab language id by its associated code first
        $langId = $this->languageMapper->fetchIdByCode($language);

        // Stop if invalid language id supplied
        if (empty($langId)) {
            return false;
        }

        $rows = $this->webPageMapper->fetchAll(array(), $langId);
        $result = array();

        // Append home URL first
        $result[] = $this->createHomeUrl($base, $language);

        foreach ($rows as $row) {
            $module = $this->cleanModuleName($row['module']);

            // Append only from active (loaded) modules
            if ($moduleManager->isLoaded($module)) {
                // Build the URL first
                $url = $base . $this->surround($row['slug'], $row['lang_id']);
                // Now make sure all special characters are escaped
                $url = htmlspecialchars($url, \ENT_QUOTES, 'UTF-8');

                $result[] = $url;
            }
        }

        return $result;
    }

    /**
     * Surrounds a slug using provided language id to generate a language code if needed
     * 
     * @param string $slug
     * @param string $langId
     * @return string
     */
    public function surround($slug, $langId)
    {
        if ($slug != null && $langId != null) {
            $languages = $this->getLanguages();

            // If we have more that one language, then URL itself should look like as /lang-code/slug/
            if (count($languages) > 1) {
                foreach ($languages as $language) {
                    if ($language['id'] == $langId) {
                        return sprintf('%s/%s/%s/', $this->baseUrl, $language['code'], $slug);
                    }
                }

            } else {
                return sprintf('%s/%s/', $this->baseUrl, $slug);
            }

        } else {
            return '';
        }
    }

    /**
     * Sluggifies a string
     * 
     * @param string $raw
     * @return string
     */
    public function sluggify($raw)
    {
        return $this->slugGenerator->generate($raw);
    }

    /**
     * Returns last inserted web page id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->webPageMapper->getLastId();
    }

    /**
     * Fetches by URL slug
     * 
     * @param string $slug
     * @return string
     */
    public function fetchBySlug($slug)
    {
        return $this->webPageMapper->fetchBySlug($slug);
    }

    /**
     * Fetches web page data its associated id
     * 
     * @param string $id Web page id
     * @return array
     */
    public function fetchById($id)
    {
        $this->findPksByModule('Shop');
        return $this->webPageMapper->fetchById($id);
    }

    /**
     * Fetch all web page records
     * 
     * @param array $excludedModules
     * @return array
     */
    public function fetchAll(array $excludedModules)
    {
        return $this->webPageMapper->fetchAll($excludedModules);
    }

    /**
     * Fetches slug by target id (Target id is id which supplies to framework controllers by dispatcher)
     * 
     * @param string $id Target id
     * @return array
     */
    public function fetchSlugByTargetId($id)
    {
        return $this->webPageMapper->fetchSlugByTargetId($id);
    }

    /**
     * Deletes web page data by its associated id
     * 
     * @param string $id Web page id
     * @param object $childMapper If any
     * @return boolean
     */
    public function deleteById($id, $childMapper = null)
    {
        $this->webPageMapper->deleteById($id);

        if ($childMapper !== null) {
            $webPageId = $childMapper->fetchWebPageIdById($id);
        }

        return true;
    }

    /**
     * Finds web page PKs by associated module
     * 
     * @param string $module Module name or module name with description
     * @return array
     */
    public function findPksByModule($module)
    {
        $module - $this->cleanModuleName($module);
        $result = array();

        foreach ($this->fetchAll() as $record) {
            if ($this->cleanModuleName($record['module']) == $module) {
                $result[] = $record['id'];
            }
        }

        return $result;
    }

    /**
     * Remove PKs by module
     * 
     * @param string $module
     * @return boolean
     */
    public function removePksByModule($module)
    {
        $ids = $this->findPksByModule($module);

        foreach ($ids as $id) {
            $this->webPageMapper->deleteById($id);
        }

        return true;
    }

    /**
     * Adds a web page
     * 
     * @param string $targetId Data to be supplied to controller
     * @param string $slug Web page slug
     * @param string $controller Web page framework's compliant controller
     * @param $childMapper Child mapper which is related to the web page being added
     * @return boolean
     */
    public function add($targetId, $slug, $module, $controller, $childMapper)
    {
        // Ensure the slug is unique
        $slug = $this->getUniqueSlug($slug);

        $this->webPageMapper->insert(array(
            'target_id'     => $targetId,
            'slug'          => $slug,
            'module'        => $module,
            'controller'    => $controller,
        ));

        return $childMapper->updateWebPageIdById($targetId, $this->webPageMapper->getLastId());
    }

    /**
     * Updates a web page
     * 
     * @param string $id Web page id
     * @param string $slug New web page slug
     * @param string $controller Optionally a controller can be updated as well
     * @return boolean
     */
    public function update($id, $slug, $controller = null)
    {
        // Before adding a new slug, make sure it has been changed
        if ($this->webPageMapper->fetchSlugByWebPageId($id) !== $slug) {
            $slug = $this->getUniqueSlug($slug);
        }

        return $this->webPageMapper->update($id, $slug, $controller);
    }

    /**
     * Cleans module name
     * 
     * @param string $module
     * @return string
     */
    private function cleanModuleName($module)
    {
        // Take out description characters
        $module = preg_replace('~(\(.*?\))~', '', $module);
        $module = trim($module);

        return $module;
    }

    /**
     * Creates home URL
     * 
     * @param string $base Base URL to be prepended
     * @param string $language
     * @return string
     */
    private function createHomeUrl($base, $language)
    {
        $languages = $this->getLanguages();

        if (count($languages) > 1) {
            return sprintf('%s/lang/%s', $base, $language);
        } else {
            return sprintf('%s/', $base);
        }
    }

    /**
     * Fetch all published languages
     * 
     * @return array
     */
    private function getLanguages()
    {
        static $languages = null;

        // Cache fetching calls
        if (is_null($languages)) {
            $languages = $this->languageMapper->fetchAll(true);
        }

        return $languages;
    }

    /**
     * Returns unique slug
     * 
     * @param string $slug
     * @return string
     */
    private function getUniqueSlug($slug)
    {
        if ($this->webPageMapper->exists($slug)) {
            $count = 0;

            while (true) {
                $count++;
                $target = sprintf('%s-%s', $slug, $count);

                if (!$this->webPageMapper->exists($target)) {
                    return $target;
                }
            }
        }

        return $slug;
    }
}
