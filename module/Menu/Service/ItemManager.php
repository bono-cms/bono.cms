<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Service;

use Menu\Storage\ItemMapperInterface;
use Menu\Storage\CategoryMapperInterface;
use Cms\Service\HistoryManagerInterface;
use Krystal\Tree\AdjacencyList\ChildrenJsonParser;
use Krystal\Security\Filter;

final class ItemManager extends AbstractItemService implements ItemManagerInterface
{
	/**
	 * Any compliant item mapper which implements ItemMapperInterface
	 * 
	 * @var \Menu\Storage\ItemMapperInterface
	 */
	private $itemMapper;

	/**
	 * Any mapper category which implements CategoryMapperInterface
	 * 
	 * @var \Menu\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * History manager is responsible for keeping track of latest actions
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Menu\Storage\ItemMapperInterface $itemMapper Any compliant mapper that implements this interface
	 * @param \Menu\Storage\CategoryMapperInterface $categoryMapperInterface Any storage adapter that implements this interface
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(ItemMapperInterface $itemMapper, CategoryMapperInterface $categoryMapper, HistoryManagerInterface $historyManager)
	{
		$this->itemMapper = $itemMapper;
		$this->categoryMapper = $categoryMapper;
		$this->historyManager = $historyManager;
	}

	/**
	 * Saves an order that has been dragged and dropped
	 * 
	 * @param string $json JSON string
	 * @return boolean
	 */
	public function save($json)
	{
		$parser = new ChildrenJsonParser();

		if ($parser->update($json, $this->itemMapper)) {
			$this->track('Menu items have been sorted');
			return true;

		} else {

			return false;
		}
	}

	/**
	 * Fetch all items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllByCategoryId($categoryId)
	{
		return $this->itemMapper->fetchAllByCategoryId($categoryId);
	}

	/**
	 * Fetches all published items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId)
	{
		return $this->itemMapper->fetchAllPublishedByCategoryId($categoryId);
	}

	/**
	 * Fetches all published items associated with given category class
	 * 
	 * @param string $class Category class
	 * @return array
	 */
	public function fetchAllPublishedByCategoryClass($class)
	{
		// Get associated id
		$id = $this->categoryMapper->fetchIdByClass($class);
		return $this->fetchAllPublishedByCategoryId($id);
	}

	/**
	 * Returns last item inserted id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->itemMapper->getLastId();
	}

	/**
	 * Adds an item
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$this->track('A new "%s" item has been created', $input['name']);
		$this->itemMapper->insert($input);

		return true;
	}

	/**
	 * Updates an item
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$this->track('The "%s" item has been updated', $input['name']);
		return $this->itemMapper->update($input);
	}

	/**
	 * Deletes an item by its associated id
	 * 
	 * @param string $id Item's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$name = Filter::escape($this->itemMapper->fetchNameById($id));

		$this->track('The "%s" item has been removed', $name);
		return $this->itemMapper->deleteById($id) && $this->itemMapper->deleteAllByParentId($id);
	}

	/**
	 * Fetches item's entity by its associated id
	 * 
	 * @param string $id
	 * @return object
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->itemMapper->fetchById($id));
	}

	/**
	 * Tracks activity
	 * 
	 * @param string $message
	 * @param string $placeholder
	 * @return boolean
	 */
	private function track($message, $placeholder = '')
	{
		return $this->historyManager->write('Menu', $message, $placeholder);
	}
}
