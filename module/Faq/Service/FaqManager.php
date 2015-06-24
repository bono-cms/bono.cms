<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Service;

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Faq\Storage\FaqMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;

final class FaqManager extends AbstractManager implements FaqManagerInterface
{
	/**
	 * Any compliant faq mapper
	 * 
	 * @var \Faq\Storage\FaqMapperInterface
	 */
	private $faqMapper;

	/**
	 * History manager to keep track
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Faq\Storage\FaqMapperInterface $faqMapper
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(FaqMapperInterface $faqMapper, HistoryManagerInterface $historyManager)
	{
		$this->faqMapper = $faqMapper;
		$this->historyManager = $historyManager;
	}

	/**
	 * Returns FAQ breadcrumbs for view layer
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $faq
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $faq)
	{
		return array(
			array(
				'name' => $faq->getTitle(),
				'link' => '#'
			)
		);
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
		return $this->historyManager->write('Faq', $message, $placeholder);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $faq)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $faq['id'])
			->setQuestion(Filter::escape($faq['question']))
			->setAnswer(Filter::escapeContent($faq['answer']))
			->setOrder((int) $faq['order'])
			->setPublished((bool) $faq['published']);

		return $entity;
	}

	/**
	 * Fetches dummy FAQ entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy()
	{
		return $this->toEntity(array(
			'id' => null,
			'question' => null,
			'answer' => null,
			'order' => null,
			'published' => true
		));
	}

	/**
	 * Updates published states by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair)
	{
		foreach ($pair as $id => $published) {
			if (!$this->faqMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Updates orders by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateOrders(array $pair)
	{
		foreach ($pair as $id => $order) {
			if (!$this->faqMapper->updateOrderById($id, $order)) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Fetches all entities filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to fetch only published ones
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage, $published)
	{
		return $this->prepareResults($this->faqMapper->fetchAllByPage($page, $itemsPerPage, $published));
	}

	/**
	 * Fetches all published entities
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->prepareResults($this->faqMapper->fetchAllPublished());
	}

	/**
	 * Adds a FAQ
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$this->track('FAQ "%s" has been added', $input['question']);
		return $this->faqMapper->insert($input);
	}

	/**
	 * Updates a FAQ
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$this->track('FAQ "%s" has been updated', $input['question']);
		return $this->faqMapper->update($input);
	}

	/**
	 * Returns last faq id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->faqMapper->getLastId();
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Pagination
	 */
	public function getPaginator()
	{
		return $this->faqMapper->getPaginator();
	}

	/**
	 * Fetches a faq bag by its associated id
	 * 
	 * @param string $id Faq id
	 * @return boolean|\Krystal\Stdlib\VirtualBag
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->faqMapper->fetchById($id));
	}

	/**
	 * Deletes a faq by its associated id
	 * 
	 * @param string $id Faq's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$name = Filter::escape($this->faqMapper->fetchQuestionById($id));

		if ($this->faqMapper->deleteById($id)) {

			$this->track('FAQ "%s" has been removed', $name);
			return true;

		} else {

			return false;
		}
	}

	/**
	 * Deletes faqs by their associated ids
	 * 
	 * @param array $ids Array of ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->faqMapper->deleteById($id)) {
				return false;
			}
		}
		
		$this->track('Batch removal of %s faq', count($ids));
		return true;
	}
}
