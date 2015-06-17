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

use Menu\Storage\CategoryMapperInterface;
use Menu\Storage\ItemMapperInterface;
use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;

final class CategoryManager extends AbstractManager implements CategoryManagerInterface
{
	/**
	 * Any mapper which implements CategoryMapperInterface
	 * 
	 * @var \Menu\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Any mapper which implements ItemMapperInterface
	 * 
	 * @var \Menu\Storage\ItemMapperInterface
	 */
	private $itemMapper;

	/**
	 * History manager is responsible for keeping track of latest actions
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Menu\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Menu\Storage\ItemMapperInterface $itemMapper
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(CategoryMapperInterface $categoryMapper, ItemMapperInterface $itemMapper, HistoryManagerInterface $historyManager)
	{
		$this->categoryMapper = $categoryMapper;
		$this->itemMapper = $itemMapper;
		$this->historyManager = $historyManager;
	}

	/**
	 * Fetches maximal category's depth level
	 * 
	 * @param string $id Category id
	 * @return integer
	 */
	public function fetchMaxDepthById($id)
	{
		return $this->categoryMapper->fetchMaxDepthById($id);
	}

	/**
	 * Fetches the first category id
	 * 
	 * @return string
	 */
	public function fetchFirstId()
	{
		return $this->categoryMapper->fetchFirstId();
	}

	/**
	 * Fetches the last inserted id
	 * 
	 * @return integer
	 */
	public function fetchLastId()
	{
		return $this->categoryMapper->fetchLastId();
	}

	/**
	 * Returns last inserted id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->categoryMapper->getLastId();
	}

	/**
	 * Fetches unique category classes
	 * 
	 * @return array
	 */
	public function fetchClasses()
	{
		return $this->categoryMapper->fetchClasses();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $category)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $category['id'])
			->setName(Filter::escape($category['name']))
			->setClass(Filter::escape($category['class']))
			->setMaxDepth((int) $category['max_depth']);
		
		return $entity;
	}

	/**
	 * Fetches dummy category bag
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy()
	{
		return $this->toEntity(array(
			'id' => null,
			'name' => null,
			'class' => null,
			'order' => null,
			'max_depth' => 5
		));
	}

	/**
	 * Adds a category
	 * 
	 * @param array $input
	 * @return boolean
	 */
	public function add(array $input)
	{
		$this->track('Category menu "%s" has been created', $input['name']);
		return $this->categoryMapper->insert($input);
	}

	/**
	 * Updates a category
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data)
	{
		$this->track('Category menu "%s" has been updated', $data['name']);
		return $this->categoryMapper->update($data);
	}

	/**
	 * Deletes a category by its associated id
	 * Also remove items associated with given category id
	 * 
	 * @param string $id
	 * @return boolean Depending on success
	 */
	public function deleteById($id)
	{
		$name = Filter::escape($this->categoryMapper->fetchNameById($id));
		
		$this->track('Category menu "%s" has been removed', $name);
		return $this->categoryMapper->deleteById($id) && $this->itemMapper->deleteAllByCategoryId($id);
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

	/**
	 * Fetches all category entities
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->prepareResults($this->categoryMapper->fetchAll());
	}

	/**
	 * Fetches a category bag by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->categoryMapper->fetchById($id));
	}
}
