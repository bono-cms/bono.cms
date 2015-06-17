<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

use News\Storage\CategoryMapperInterface;
use News\Storage\PostMapperInterface;
use Cms\Service\WebPageManagerInterface;
use Krystal\Image\Tool\ImageManagerInterface;

/* Internal service to remove categories */
final class CategoryRemover implements CategoryRemoverInterface
{
	/**
	 * Any compliant category mapper
	 * 
	 * @var \News\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Any compliant post mapper
	 * 
	 * @var \News\Storage\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * Web page manager to deal with slugs
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * Image manager to remove post images when removing a category
	 * 
	 * @var \Krystal\Image\Tool\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * State initialization
	 * 
	 * @param \News\Storage\CategoryMapperInterface $categoryMapper
	 * @param \News\Storage\PostMapperInterface $postMapper
	 * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function __construct(
		CategoryMapperInterface $categoryMapper, 
		PostMapperInterface $postMapper, 
		ImageManagerInterface $imageManager, 
		WebPageManagerInterface $webPageManager
	){
		$this->categoryMapper = $categoryMapper;
		$this->postMapper = $postMapper;
		$this->imageManager = $imageManager;
		$this->webPageManager = $webPageManager;
	}

	/**
	 * Completely removes a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	public function removeAllById($id)
	{
		// The order of execution is important
		$this->removeCategoryById($id);
		$this->removeAllPostImagesByCategoryId($id);
		$this->removeAllPostsById($id);

		return true;
	}

	/**
	 * Removes a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removeCategoryById($id)
	{
		$webPageId = $this->categoryMapper->fetchWebPageIdById($id);

		$this->webPageManager->deleteById($webPageId);
		$this->categoryMapper->deleteById($id);

		return true;
	}

	/**
	 * Removes all post images associated with category id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removeAllPostImagesByCategoryId($id)
	{
		$ids = $this->postMapper->fetchAllIdsWithImagesByCategoryId($id);

		// Do the work, in case there's at least one id
		if (!empty($ids)) {
			foreach ($ids as $id) {
				$this->imageManager->delete($id);
			}
		}

		return true;
	}

	/**
	 * Removes all posts associated with category id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removeAllPostsById($id)
	{
		$webPageIds = $this->postMapper->fetchAllWebPageIdsByCategoryId($id);

		// Remove associated web pages, first
		foreach ($webPageIds as $webPageId) {
			$this->webPageManager->deleteById($webPageId);
		}

		// And then remove all posts
		$this->postMapper->deleteAllByCategoryId($id);

		return true;
	}
}
