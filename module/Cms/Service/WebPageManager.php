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
	 * State initialization
	 * 
	 * @param \Cms\Storage\WebPageMapperInterface $webPageMapper
	 * @param \Cms\Storage\LanguageMapperInterface $languageMapper
	 * @param \Krystal\Text\SlugGenerator $slugGenerator
	 * @return void
	 */
	public function __construct(WebPageMapperInterface $webPageMapper, LanguageMapperInterface $languageMapper, SlugGenerator $slugGenerator)
	{
		$this->webPageMapper = $webPageMapper;
		$this->languageMapper = $languageMapper;
		$this->slugGenerator = $slugGenerator;
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
	 * Surrounds a slug using provided language id to generate a language code if needed
	 * 
	 * @param string $slug
	 * @param string $langId
	 * @return string
	 */
	public function surround($slug, $langId)
	{
		if ($slug != null && $langId != null) {
			static $languages = null;

			// Cache fetching calls
			if (is_null($languages)) {
				$languages = $this->languageMapper->fetchAllPublished();
			}

			// If we have more that one language, then URL itself should look like as /lang-code/slug/
			if (count($languages) > 1) {
				foreach ($languages as $language) {
					if ($language['id'] == $langId) {
						return sprintf('/%s/%s/', $language['code'], $slug);
					}
				}

			} else {
				return '/' . $slug . '/';
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
		return $this->webPageMapper->fetchById($id);
	}

	/**
	 * Fetch all web page records
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->webPageMapper->fetchAll();
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
			'target_id'		=> $targetId,
			'slug' 			=> $slug,
			'module'		=> $module,
			'controller'	=> $controller,
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
}
