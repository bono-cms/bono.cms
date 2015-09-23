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
use Shop\Storage\CategoryMapperInterface;
use Cms\Service\AbstractManager;

final class TaskManager extends AbstractManager implements TaskManagerInterface
{
	/**
	 * Any compliant that implements ProductMapperInterface
	 * 
	 * @var \Shop\Storage\ProductMapperInterface
	 */
	private $productMapper;

	/**
	 * A mapper that implements CategoryMapperInterface
	 * 
	 * @var \Shop\Storage\CategoryManagerInterface
	 */
	private $categoryManager;

	/**
	 * State initialization
	 * 
	 * @param \Shop\Storage\ProductMapperInterface $productMapper
	 * @param \Shop\Storage\CategoryManagerInterface $categoryManager
	 * @return void
	 */
	public function __construct(ProductMapperInterface $productMapper, CategoryManagerInterface $categoryManager)
	{
		$this->productMapper = $productMapper;
		$this->categoryManager = $categoryManager;
	}

	/**
	 * Fetches category's entity by its associated id
	 * 
	 * @param string $id Category id
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchByCategoryId($id)
	{
		return $this->categoryManager->fetchById($id);
	}

	/**
	 * Counts amount of products associated with provided category id
	 * 
	 * @param string $id Category id
	 * @return integer
	 */
	public function getProductCountByCategoryId($id)
	{
		//@TODO: Add caching layer
		return $this->productMapper->countAllByCategoryId($id);
	}
}
