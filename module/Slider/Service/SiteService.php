<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Service;

use Krystal\Cache\CacheEngineInterface;

/**
 * This service automatically gets injected to templates
 */
final class SiteService implements SiteServiceInterface
{
	/**
	 * Cache engine used to cache fetching calls
	 * 
	 * @var \Krystal\Cache\CacheEngineInterface
	 */
	private $cache;

	/**
	 * Image manages which provides slide bags
	 * 
	 * @var \Slider\Service\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * State initialization
	 * 
	 * @param \Slider\Service\ImageManagerInterface $imageManager
	 * @param \Krystal\Cache\CacheEngineInterface $cache
	 * @return void
	 */
	public function __construct(ImageManagerInterface $imageManager, CacheEngineInterface $cache)
	{
		$this->imageManager = $imageManager;
		$this->cache = $cache;
	}

	/**
	 * Returns data and caches for the next call
	 * 
	 * @param string $class Category's class name
	 * @return array|boolean
	 */
	private function getData($class)
	{
		if ($this->cache->has($class)) {
			$data = $this->cache->get($class);
		} else {
			$data = $this->imageManager->fetchAllPublishedByCategoryClass($class);
			$this->cache->set($class, $data, 0);
		}

		return $data;
	}

	/**
	 * Checks whether provided category's class has at least one slide image
	 * 
	 * @param string $class
	 * @return boolean
	 */
	public function has($class)
	{
		return (bool) $this->getAll($class);
	}

	/**
	 * Returns slide bags from given category class
	 * 
	 * @param string $class Category's class name
	 * @return array
	 */
	public function getAll($class)
	{
		return $this->getData($class);
	}
}
