<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

use Krystal\Tree\AdjacencyList\TreeBuilder;
use Krystal\Image\Tool\ImageManagerInterface;
use Shop\Storage\CategoryMapperInterface;
use Cms\Service\WebPageManagerInterface;

/**
 * Internal service which takes care of category removal
 */
final class CategoryRemover
{
	/**
	 * Any compliant category mapper
	 * 
	 * @var \Shop\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Web page manager is used to remove web pages associated with category ids
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * Image manager is used to remove images associated with category ids
	 * 
	 * @var \Krystal\Image\Tool\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * Product remover removes all products which belong to a category id
	 * 
	 * @var \Shop\Service\ProductRemoverInterface
	 */
	private $productRemover;

	/**
	 * State initialization
	 * 
	 * @param \Shop\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
	 * @param \Shop\Service\ProductRemoverInterface $productRemover
	 * @return void
	 */
	public function __construct(
		CategoryMapperInterface $categoryMapper, 
		WebPageManagerInterface $webPageManager, 
		ImageManagerInterface $imageManager, 
		ProductRemoverInterface $productRemover
	){
		$this->categoryMapper = $categoryMapper;
		$this->webPageManager = $webPageManager;
		$this->imageManager = $imageManager;
		$this->productRemover = $productRemover;
	}

	/**
	 * Fully removes a category
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	public function removeAllById($id)
	{
		$this->removeWebPageById($id);
		$this->removeCategoryById($id);
		$this->removeChildNodes($id);

		return true;
	}

	/**
	 * Removes a category by its associated id (Including images if present)
	 * 
	 * @param string $id
	 * @return boolean
	 */
	private function removeCategoryById($id)
	{
		$this->categoryMapper->deleteById($id);
		$this->imageManager->delete($id);

		// Remove associated products
		$this->productRemover->removeAllProductsByCategoryId($id);

		return true;
	}
	
	/**
	 * Removes all child nodes
	 * 
	 * @param string $parentId Parent category's id
	 * @return boolean
	 */
	private function removeChildNodes($parentId)
	{
		$treeBuilder = new TreeBuilder($this->categoryMapper->fetchAll());
		$ids = $treeBuilder->findChildNodeIds($parentId);

		// If there's at least one child id, then start working next
		if (!empty($ids)) {
			foreach ($ids as $id) {
				$this->removeCategoryById($id);
			}
		}

		return true;
	}

	/**
	 * Removes category's web page
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	private function removeWebPageById($id)
	{
		$webPageId = $this->categoryMapper->fetchWebPageIdById($id);
		return $this->webPageManager->deleteById($webPageId);
	}
}
