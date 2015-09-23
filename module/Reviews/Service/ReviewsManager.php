<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Service;

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Reviews\Storage\ReviewsMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;

final class ReviewsManager extends AbstractManager implements ReviewsManagerInterface
{
	/**
	 * Any mapper that implements this interface
	 * 
	 * @var \Review\Storage\ReviewsMapperInterface
	 */
	private $reviewsMapper;

	/**
	 * History manager
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Review\Storage\ReviewsMapperInterface $reviewsMapper
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(ReviewsMapperInterface $reviewsMapper, HistoryManagerInterface $historyManager)
	{
		$this->reviewsMapper = $reviewsMapper;
		$this->historyManager = $historyManager;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $review)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $review['id'])
				  ->setTimestamp((int) $review['timestamp'])
				  ->setIp($review['ip'])
				  ->setPublished((bool) $review['published'])
				  ->setName(Filter::escape($review['name']))
				  ->setEmail(Filter::escape($review['email']))
				  ->setReview(Filter::escapeContent($review['review']));

		return $entity;
	}

	/**
	 * Updates published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair)
	{
		foreach ($pair as $id => $published) {
			if (!$this->reviewsMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}

		$this->historyManager->write('Reviews', 'Batch update of %s reviews', count($pair));
		return true;
	}
	
	/**
	 * Deletes reviews by theirs associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->reviewsMapper->deleteById($id)) {
				return false;
			}
		}
		
		$this->track('Batch removal of %s reviews', count($ids));
		return true;
	}

	/**
	 * Deletes a review by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$name = Filter::escape($this->reviewsMapper->fetchNameById($id));

		if ($this->reviewsMapper->deleteById($id)) {

			$this->track('A review by "%s" has been removed', $name);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->reviewsMapper->getPaginator();
	}

	/**
	 * Returns default time format
	 * 
	 * @return string
	 */
	public function getTimeFormat()
	{
		return 'm/d/Y';
	}

	/**
	 * Fetches all published reviews filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items to be shown per page
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->reviewsMapper->fetchAllPublishedByPage($page, $itemsPerPage));
	}

	/**
	 * Fetches all reviews filtered pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items to be shown per page
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->reviewsMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Returns last id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->reviewsMapper->getLastId();
	}

	/**
	 * Fetches a review by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->reviewsMapper->fetchById($id));
	}

	/**
	 * Prepares data container before sending to a mapper
	 * 
	 * @param array $data
 	 * @return void
	 */
	private function prepareContainer(array $data)
	{
		$data['timestamp'] = strtotime($data['date']);
		return ArrayUtils::arrayWithout($data, array('date'));
	}

	/**
	 * Adds a review
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$this->track('A new review by "%s" has been added', $input['name']);

		$input = $this->prepareContainer($input);
		return $this->reviewsMapper->insert($input);
	}

	/**
	 * Sends data from a user
	 * 
	 * @param array $input Raw input data
	 * @param boolean $enableModeration Whether a review should be moderated or not
	 * @return boolean
	 */
	public function send(array $input, $enableModeration)
	{
		// Always current timestamp
		$input['timestamp'] = time();

		// This value depends on configuration, where we handled moderation
		if ($enableModeration) {
			$input['published'] = '0';
		} else {
			$input['published'] = '1';
		}

		return $this->reviewsMapper->insert(ArrayUtils::arrayWithout($input, array('captcha')));
	}

	/**
	 * Updates a review
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$this->track('A review by "%s" has been updated', $input['name']);

		$input = $this->prepareContainer($input);
		return $this->reviewsMapper->update($input);
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
		return $this->historyManager->write('Reviews', $message, $placeholder);
	}
}
