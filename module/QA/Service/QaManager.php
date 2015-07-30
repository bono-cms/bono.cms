<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa\Service;

use Cms\Service\HistoryManagerInterface;
use Cms\Service\AbstractManager;
use Qa\Storage\QaMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;

final class QaManager extends AbstractManager implements QaManagerInterface
{
	/**
	 * Any compliant QA mapper
	 * 
	 * @var \QA\Storage\QaMapperInterface
	 */
	private $qaMapper;

	/**
	 * History manager to keep track
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \QA\Storage\QaMapperInterface $qaMapper
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(QaMapperInterface $qaMapper, HistoryManagerInterface $historyManager)
	{
		$this->qaMapper = $qaMapper;
		$this->historyManager = $historyManager;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $qa)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $qa['id'])
			  ->setLangId((int) $qa['lang_id'])
			  ->setQuestion(Filter::escape($qa['question']))
			  ->setAnswer(Filter::escapeContent($qa['answer']))
			  ->setQuestioner(Filter::escape($qa['questioner']))
			  ->setAnswerer(Filter::escape($qa['answerer']))
			  ->setPublished((bool) $qa['published'])
			  ->setTimestampAsked((int) $qa['timestamp_asked'])
			  ->setTimestampAnswered((int) $qa['timestamp_answered'])
			  ->setDateAsked(strtotime($qa['timestamp_asked']))
			  ->setDateAnswered(strtotime($qa['timestamp_answered']));

		return $entity;
	}

	/**
	 * Delete QA pairs by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->qaMapper->deleteById($id)) {
				return false;
			}
		}
		
		$this->track('%s questions have been removed', count($ids));
		return true;
	}

	/**
	 * Deletes QA pair by its associated id
	 * 
	 * @param string $id QA id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		// Grab question title before we remove id
		$question = Filter::escape($this->qaMapper->fetchQuestionById($id));

		if ($this->qaMapper->deleteById($id)) {
			$this->track('Question "%s" has been removed', $question);
			return true;
			
		} else {
			return false;
		}
	}

	/**
	 * Updates published states by their associate ids
	 * 
	 * @param array $pair Array of ids
	 * @return boolean
	 */
	public function updatePublished(array $pair)
	{
		foreach ($pair as $id => $published) {
			if (!$this->qaMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}

		return true;
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
	 * Returns last QA id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->qaMapper->getLastId();
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->qaMapper->getPaginator();
	}

	/**
	 * Fetches question bag by its associated id
	 * 
	 * @param string $id
	 * @return \Krystal\Stdlib\VirtualBag|boolean
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->qaMapper->fetchById($id));
	}

	/**
	 * Fetches all QA bags filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->qaMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Prepare raw input data before sending to a mapper
	 * 
	 * @param array $data
	 * @return void
	 */
	private function prepareInput(array $input)
	{
		$input['timestamp_asked'] = strtotime($input['date_asked']);
		$input['timestamp_answered'] = strtotime($input['date_answered']);

		return ArrayUtils::arrayWithout($input, array('date_asked', 'date_answered'));
	}

	/**
	 * Adds a QA pair
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);
		$this->track('Question "%s" has been created', $input['question']);
		
		return $this->qaMapper->insert($input);
	}

	/**
	 * Updates QA pair
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$input = $this->prepareInput($input);
		$this->track('Question "%s" has been updated', $input['question']);

		return $this->qaMapper->update($input);
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
		return $this->historyManager->write('QA', $message, $placeholder);
	}
}
