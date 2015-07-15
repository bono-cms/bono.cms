<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Service;

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Slider\Storage\CategoryMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;

final class CategoryManager extends AbstractManager implements CategoryManagerInterface
{
	/**
	 * Any compliant category mapper 
	 *  
	 * @var \Slider\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * History manager to keep track
	 * 
	 * @var \Cms\Service\HistoryManager
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Slider\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(CategoryMapperInterface $categoryMapper, HistoryManagerInterface $historyManager)
	{
		$this->categoryMapper = $categoryMapper;
		$this->historyManager = $historyManager;
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
	 * Tracks activity
	 * 
	 * @param string $message
	 * @param string $placeholder
	 * @return boolean
	 */
	private function track($message, $placeholder)
	{
		return $this->historyManager->write('Slider', $message, $placeholder);
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
	 * {@inheritDoc}
	 */
	protected function toEntity(array $category)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $category['id'])
			->setName(Filter::escape($category['name']))
			->setClass(Filter::escape($category['class']))
			// How to escape this ones? As a float? Or as integer?
			->setWidth($category['width'])
			->setHeight($category['height']);
		
		return $entity;
	}

	/**
	 * Fetch category's entity by associated id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->categoryMapper->fetchById($id));
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
	 * Deletes a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */ 
	public function deleteById($id)
	{
		$name = Filter::escape($this->categoryMapper->fetchNameById($id));

		if ($this->categoryMapper->deleteById($id)) {

			$this->track('Category "%s" has been removed', $name);
			return true;

		} else {

			return false;
		}
	}

	/**
	 * Adds a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$this->track('Category "%s" has been added', $input['name']);
		return $this->categoryMapper->insert($input);
	}

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$this->track('Category "%s" has been updated', $input['name']);
		return $this->categoryMapper->update($input);
	}
}
