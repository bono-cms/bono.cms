<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace QA\Service;

use Cms\Service\HistoryManagerInterface;
use Cms\Service\AbstractManager;
use QA\Storage\QaMapperInterface;
use Krystal\Stdlib\VirtualEntity;
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
			  ->setLangId((int) $qa['langId'])
			  ->setQuestion(Filter::escape($qa['question']))
			  ->setAnswer(Filter::escapeContent($qa['answer']))
			  ->setQuestioner(Filter::escape($qa['questioner']))
			  ->setAnswerer(Filter::escape($qa['answerer']))
			  ->setPublished((bool) $qa['published'])
			  ->setTimestampAsked((int) $qa['timestampAsked'])
			  ->setTimestampAnswered((int) $qa['timestampAnswered'])
			  ->setDateAsked(strtotime($qa['timestampAsked']))
			  ->setDateAnswered(strtotime($qa['timestampAnswered']));
		
		return $entity;
	}

	/**
	 * Fetches dummy QA entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy()
	{
		return $this->toEntity(array(
			'id' => null,
			'langId' => null,
			'question' => null,
			'answer' => null,
			'questioner' => null,
			'answerer' => null,
			'published' => true,
			'timestampAsked' => time(),
			'timestampAnswered' => time()
		));
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
	 * Prepare a container before sending to a mapper
	 * 
	 * @param array $data
	 * @return void
	 */
	private function prepareContainer(array $data)
	{
		$data['timestampAsked']	= strtotime($data['dateAsked']);
		$data['timestampAnswered'] = strtotime($data['dateAnswered']);

		return $data;
	}

	/**
	 * Adds a QA pair
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$data = $this->prepareContainer($data);
		$this->track('Question "%s" has been created', $data['question']);
		
		return $this->qaMapper->insert($data);
	}

	/**
	 * Updates QA pair
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$data = $this->prepareContainer($input);
		$this->track('Question "%s" has been updated', $data['question']);

		return $this->qaMapper->update($data);
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
