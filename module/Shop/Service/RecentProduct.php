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

use Krystal\Text\CsvLimitedToolInterface;
use Krystal\Http\PersistentStorageInterface;

final class RecentProduct implements RecentProductInterface
{
	/**
	 * Utility to manage collection of product ids
	 * 
	 * @var \Krystal\Text\CsvLimitedToolInterface
	 */
	private $csvLimitedTool;

	/**
	 * Product manager to fetch products details by their associated ids
	 * 
	 * @var \Shop\Service\ProductManager
	 */
	private $productManager;

	/**
	 * Any compliant persistence storage manager
	 * 
	 * @var \Krystal\Http\PersistentStorageInterface
	 */
	private $storage;

	/**
	 * Unique storage key which represents related data
	 * 
	 * @const string
	 */
	const STORAGE_KEY = 'rcp';

	/**
	 * State initialization
	 * 
	 * @param \Shop\Service\ProductManagerInterface $productManager
	 * @param \Krystal\Text\CsvLimitedToolInterface $csvLimitedTool
	 * @param \Krystal\Http\PersistentStorageInterface $storage
	 * @return void
	 */
	public function __construct(ProductManagerInterface $productManager, CsvLimitedToolInterface $csvLimitedTool, PersistentStorageInterface $storage)
	{
		$this->productManager = $productManager;
		$this->csvLimitedTool = $csvLimitedTool;
		$this->storage = $storage;
	}

	/**
	 * Returns a collection of recent products
	 * 
	 * @param string $id Current product id
	 * @return array
	 */
	public function getWithRecent($id)
	{
		$this->prepend($id);
		$this->save();

		// On current page we don't want current product id to be shown in case it already exists in the stack
		if ($this->has($id)) {
			$this->remove($id);
		}

		return $this->getAll();
	}

	/**
	 * Removes a product id from the stack
	 * 
	 * @param string $id Product id
	 * @return boolean
	 */
	public function remove($id)
	{
		return $this->csvLimitedTool->remove($id);
	}

	/**
	 * Checks whether we have a product id in the stack
	 * 
	 * @param string $id Product id
	 * @return boolean
	 */
	public function has($id)
	{
		return $this->csvLimitedTool->exists($id);
	}

	/**
	 * Returns all recent product entities
	 * 
	 * @return array
	 */
	public function getAll()
	{
		$entities = array();
		$ids = $this->csvLimitedTool->getValues(false);

		foreach ($ids as $id) {
			// Additional security check
			if (is_numeric($id)) {
				$entity = $this->productManager->fetchById($id);

				// Yet another security check, which ensures that product exists
				if ($entity !== false) {
					array_push($entities, $entity);
				}
			}
		}

		return $entities;
	}

	/**
	 * Prepends a recent product's id
	 * 
	 * @param string $id Product id
	 * @return boolean
	 */
	public function prepend($id)
	{
		return $this->csvLimitedTool->prepend($id);
	}

	/**
	 * Loads data from a storage
	 * 
	 * @return void
	 */
	public function load()
	{
		$data = '';

		if ($this->storage->has(self::STORAGE_KEY)) {
			$data = $this->storage->get(self::STORAGE_KEY);
		}

		$this->csvLimitedTool->load($data);
	}

	/**
	 * Saves data (usually with its all modifications) to a storage
	 * 
	 * @return boolean
	 */
	public function save()
	{
		$data = $this->csvLimitedTool->getValues();
		$this->storage->set(self::STORAGE_KEY, $data, 631139040);

		return true;
	}
}
