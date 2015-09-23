<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

use Shop\Storage\ProductMapperInterface;
use Shop\Storage\ImageMapperInterface;
use Cms\Service\WebPageManagerInterface;
use Krystal\Image\Tool\ImageManagerInterface;

/* Internal service which takes care of product removal */
final class ProductRemover implements ProductRemoverInterface
{
	/**
	 * Any compliant product mapper
	 * 
	 * @var \Shop\Storage\ProductMapperInterface
	 */
	private $productMapper;

	/**
	 * Product's image mapper
	 * 
	 * @var \Shop\Storage\ImageMapperInterface
	 */
	private $imageMapper;

	/**
	 * Web page manager is used to remove web pages by associated product ids
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * Image manager to delete images by product ids
	 * 
	 * @var \Krystal\Image\Tool\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * State initialization
	 * 
	 * @param \Shop\Storage\ProductMapperInterface $productMapper
	 * @param \Shop\Storage\ImageMapperInterface $imageMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
	 * @return void
	 */
	public function __construct(
		ProductMapperInterface $productMapper, 
		ImageMapperInterface $imageMapper, 
		WebPageManagerInterface $webPageManager, 
		ImageManagerInterface $imageManager
	){
		$this->productMapper = $productMapper;
		$this->imageMapper = $imageMapper;
		$this->webPageManager = $webPageManager;
		$this->imageManager = $imageManager;
	}

	/**
	 * Completely removes a product by its associated id
	 * 
	 * @param string $id Product's id
	 * @return boolean
	 */
	public function removeAllById($id)
	{
		return $this->removeWebPageById($id) && $this->removeById($id);
	}

	/**
	 * Removes all associated product ids with given category id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	public function removeAllProductsByCategoryId($id)
	{
		$ids = $this->productMapper->fetchProductIdsByCategoryId($id);

		if (!empty($ids)) {
			foreach ($ids as $id) {
				//@TODO test it
				$this->removeAllById($id);
			}
		}

		return true;
	}

	/**
	 * Removes product's web page
	 * 
	 * @param string $id Product's id
	 * @return boolean
	 */
	private function removeWebPageById($id)
	{
		$webPageId = $this->productMapper->fetchWebPageIdById($id);
		return $this->webPageManager->deleteById($webPageId);
	}

	/**
	 * Removes a product by its associated id
	 * 
	 * @param string $id Product id
	 * @return boolean
	 */
	private function removeById($id)
	{
		// Remove from a storage first
		$this->productMapper->deleteById($id);
		$this->imageMapper->deleteAllByProductId($id);

		// Now remove from the file-system
		$this->imageManager->delete($id);

		return true;
	}
}
