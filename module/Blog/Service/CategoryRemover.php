<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

use Blog\Storage\CategoryMapperInterface;
use Blog\Storage\PostMapperInterface;
use Cms\Service\WebPageManagerInterface;

/* Internal service to remove categories */
final class CategoryRemover implements CategoryRemoverInterface
{
	/**
	 * Any-compliant category mapper
	 * 
	 * @var \Blog\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Any compliant post mapper
	 * 
	 * @var \Blog\Storage\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * Web page manager to remove post web pages
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * State initialization
	 * 
	 * @param \Blog\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Blog\Storage\PostMapperInterface $postMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function __construct(CategoryMapperInterface $categoryMapper, PostMapperInterface $postMapper, WebPageManagerInterface $webPageManager)
	{
		$this->categoryMapper = $categoryMapper;
		$this->postMapper = $postMapper;
		$this->webPageManager = $webPageManager;
	}
	
	/**
	 * Removes a category and its associated posts
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	public function removeAllById($id)
	{
		return $this->removeCategoryById($id) && $this->removeAllPostsByCategoryId($id);
	}

	/**
	 * Removes all web pages associated with category id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removePostWebPagesByCategoryId($id)
	{
		$ids = $this->postMapper->fetchWebPageIdsByCategoryId($id);

		if (!empty($ids)) {
			foreach ($ids as $id) {
				$this->webPageManager->deleteById($id);
			}
		}

		return true;
	}

	/**
	 * Removes web page associated with provided category id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removeWebPageByCategoryId($id)
	{
		$webPageId = $this->categoryMapper->fetchWebPageIdById($id);
		return $this->webPageManager->deleteById($webPageId);
	}

	/**
	 * Removes a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removeCategoryById($id)
	{
		return $this->removeWebPageByCategoryId($id) && $this->categoryMapper->deleteById($id);
	}

	/**
	 * Removes all posts associated with provided category id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	private function removeAllPostsByCategoryId($id)
	{
		return $this->removePostWebPagesByCategoryId($id) && $this->postMapper->deleteByCategoryId($id);
	}
}
