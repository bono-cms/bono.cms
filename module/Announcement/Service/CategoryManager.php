<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Service;

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Announcement\Storage\AnnounceMapperInterface;
use Announcement\Storage\CategoryMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;

final class CategoryManager extends AbstractManager implements CategoryManagerInterface
{
	/**
	 * Any-compliant announce mapper
	 * 
	 * @var \Announcement\Storage\AnnounceMapperInterface
	 */
	private $announceMapper;

	/**
	 * Any compliant category mapper
	 * 
	 * @var \Announcement\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * History manager to track activity
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Announcement\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Announcement\Storage\AnnounceMapperInterface $announceMapper
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(CategoryMapperInterface $categoryMapper, AnnounceMapperInterface $announceMapper, HistoryManagerInterface $historyManager)
	{
		$this->categoryMapper = $categoryMapper;
		$this->announceMapper = $announceMapper;
		$this->historyManager = $historyManager;
	}

	/**
	 * Tracks activity
	 * 
	 * @param string $message
	 * @pram string $placeholder
	 * @return boolean
	 */
	private function track($message, $placeholder)
	{
		return $this->historyManager->write('Announcement', $message, $placeholder);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $category)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $category['id'])
			->setName(Filter::escape($category['name']))
			->setClass(Filter::escape($category['class']));

		return $entity;
	}

	/**
	 * Fetches as a list
	 * 
	 * @return array
	 */
	public function fetchList()
	{
		return ArrayUtils::arrayList($this->categoryMapper->fetchList(), 'id', 'name');
	}

	/**
	 * Fetches category data by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean|\Krystal\Stdlib\VirtualEntity
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->categoryMapper->fetchById($id));
	}

	/**
	 * Returns last category id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->categoryMapper->getLastId();
	}

	/**
	 * Fetches all category bags
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->prepareResults($this->categoryMapper->fetchAll());
	}

	/**
	 * Deletes a category by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		// Grab category's name before we remove it
		$name = Filter::escape($this->categoryMapper->fetchNameById($id));

		if ($this->categoryMapper->deleteById($id) && $this->announceMapper->deleteAllByCategoryId($id)) {
			$this->track('Category "%s" has been removed', $name);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw form data
	 * @return boolean Depending on success
	 */
	public function update(array $input)
	{
		if ($this->categoryMapper->update($input['id'], $input['name'], $input['class'])) {
			$this->track('Category "%s" has been updated', $input['name']);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Adds a category
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$this->track('Category "%s" has been added', $input['name']);
		return $this->categoryMapper->insert($input['name'], $input['class']);
	}
}
