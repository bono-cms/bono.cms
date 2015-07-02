<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Service;

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Image\Tool\ImageManagerInterface;
use Krystal\Security\Filter;
use Team\Storage\TeamMapperInterface;

final class TeamManager extends AbstractManager implements TeamManagerInterface
{
	/**
	 * Any compliant team mapper
	 * 
	 * @var \Team\Storage\TeamMapperInterface
	 */
	private $teamMapper;

	/**
	 * Image manager to deal with images
	 * 
	 * @var \Krystal\Image\Tool\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * History manager to keep track
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Team\Storage\TeamMapperInterface $teamMapper
	 * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
	 * @param \Cms\Service\HistoryManagerInterface
	 * @return void
	 */
	public function __construct(TeamMapperInterface $teamMapper, ImageManagerInterface $imageManager, HistoryManagerInterface $historyManager)
	{
		$this->teamMapper = $teamMapper;
		$this->imageManager = $imageManager;
		$this->historyManager = $historyManager;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $member)
	{
		$imageBag = clone $this->imageManager->getImageBag();
		$imageBag->setId($member['id'])
				 ->setCover($member['photo']);

		$entity = new VirtualEntity();
		$entity->setImageBag($imageBag)
				  ->setId((int) $member['id'])
				  ->setName(Filter::escape($member['name']))
				  ->setDescription(Filter::escapeContent($member['description']))
				  ->setPhoto(Filter::escape($member['photo']))
				  ->setPublished((bool) $member['published'])
				  ->setOrder((int) $member['order']);
		
		return $entity;
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->teamMapper->getPaginator();
	}

	/**
	 * Fetches all member entities filtered by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->teamMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Fetches all published member entities filtered by pagination
	 * 
	 * @param integer $page Page number
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->teamMapper->fetchAllPublishedByPage($page, $itemsPerPage));
	}

	/**
	 * Returns last member id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->teamMapper->getLastId();
	}

	/**
	 * Adds a member
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		// Ensure we have a file
		if (!empty($input['files'])) {

			$file =& $input['files']['file'];
			$this->filterFileInput($file);

			// Append new photo key into data container
			$input['data']['photo'] = $file[0]->getName();

			$this->track('Member "%s" has been added', $input['data']['name']);

			// Insert must be first, so that we can get the last id
			return $this->teamMapper->insert($input['data']) && $this->imageManager->upload($this->getLastId(), $file);
		}
	}

	/**
	 * Updates a member
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function update(array $input)
	{
		// Just a reference
		$form =& $input['data'];

		if (!empty($input['files'])) {

			$file =& $input['files']['file'];

			// When overriding a photo, we need to remove old one from the file-system
			if ($this->imageManager->delete($form['id'], $form['photo'])) {

				// Filter names
				$this->filterFileInput($file);

				// Now upload a new one
				$form['photo'] = $file[0]->getName();
				$this->imageManager->upload($form['id'], $file);

			} else {

				// Failed to remove old photo:
				return false;
			}
		}

		$this->track('Member "%s" has been updated', $form['name']);
		return $this->teamMapper->update($form);
	}

	/**
	 * Fetches member's entity by associated id
	 * 
	 * @param string $id Member's id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->teamMapper->fetchById($id));
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
		return $this->historyManager->write('Team', $message, $placeholder);
	}

	/**
	 * Deletes a member
	 * 
	 * @param string $id Member's id
	 * @return boolean
	 */
	private function delete($id)
	{
		return $this->teamMapper->deleteById($id) && $this->imageManager->delete($id);
	}

	/**
	 * Removes a member by associated id
	 * 
	 * @param string $id Member id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$name = Filter::escape($this->teamMapper->fetchNameById($id));

		if ($this->delete($id)) {

			$this->track('Member "%s" has been removed', $name);
			return true;

		} else {

			return false;
		}
	}

	/**
	 * Delete members by ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->delete($id)) {
				return false;
			}
		}
		
		$this->track('Batch removal of %s members', count($ids));
		return true;
	}

	/**
	 * Update orders by their associated ids
	 * 
	 * @param array $orders
	 * @return boolean
	 */
	public function updateOrders(array $orders)
	{
		foreach ($orders as $id => $order) {
			if (!$this->teamMapper->updateOrderById($id, $order)) {
				return false;
			}
		}
		
		return true;
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
			if (!$this->teamMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}
		
		return true;
	}
}
