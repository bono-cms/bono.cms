<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Service;

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Advice\Storage\AdviceMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;

final class AdviceManager extends AbstractManager implements AdviceManagerInterface
{
	/**
	 * Any compliant advice mapper
	 * 
	 * @var \Advice\Storage\AdviceMapperInterface
	 */
	private $adviceMapper;

	/**
	 * History manager to keep track of latest actions
	 * 
	 * @var \Cms\Storage\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Advice\Storage\AdviceMapperInterface $adviceMapper
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(AdviceMapperInterface $adviceMapper, HistoryManagerInterface $historyManager)
	{
		$this->adviceMapper = $adviceMapper;
		$this->historyManager = $historyManager;
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
		return $this->historyManager->write('Advice', $message, $placeholder);
	}

	/**
	 * Update published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair)
	{
		foreach ($pair as $id => $published) {
			if (!$this->adviceMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Fetches dummy advice entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy()
	{
		return $this->toEntity(array(
			'id' => null,
			'lang_id' => null,
			'title' => null,
			'content' => null,
			'published' => true
		));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $advice)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $advice['id'])
				->setLangId((int) $advice['lang_id'])
				->setTitle(Filter::escape($advice['title']))
				->setContent(Filter::escapeContent($advice['content']))
				->setPublished((bool) $advice['published']);
		
		return $entity;
	}

	/**
	 * Fetches random advice entity
	 * 
	 * @return boolean|\Krystal\Stdlib\VirtualEntity
	 */
	public function fetchRandom()
	{
		return $this->prepareResult($this->adviceMapper->fetchRandom());
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->adviceMapper->getPaginator();
	}

	/**
	 * Returns last advice id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->adviceMapper->getLastId();
	}

	/**
	 * Adds an advice
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$this->track('Advice "%s" has been added', $input['title']);
		return $this->adviceMapper->insert($input['title'], $input['content'], $input['published']);
	}

	/**
	 * Updates an advice
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$this->track('Advice "%s" has been updated', $input['title']);
		return $this->adviceMapper->update($input['id'], $input['title'], $input['content'], $input['published']);
	}

	/**
	 * Fetches all advice entities filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->adviceMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Deletes an advice by its associated id
	 * 
	 * @param string $id Advice id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		// Grab advice's title before we remove id
		$title = Filter::escape($this->adviceMapper->fetchTitleById($id));

		if ($this->adviceMapper->deleteById($id)) {
			$this->track('Advice "%s" has been removed', $title);
			return true;
			
		} else {
			
			return false;
		}
	}

	/**
	 * Delete advices by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->adviceMapper->deleteById($id)) {
				return false;
			}
		}
		
		$this->track('Batch removal of %s advices', count($ids));
		return true;
	}

	/**
	 * Fetches advice's entity by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->adviceMapper->fetchById($id));
	}
}
