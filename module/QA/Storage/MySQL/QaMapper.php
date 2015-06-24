<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace QA\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use QA\Storage\QaMapperInterface;

final class QaMapper extends AbstractMapper implements QaMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_qa';

	/**
	 * Returns shared select
	 * 
	 * @param boolean $published
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published)
	{
		$db = $this->db->select('*')
					   ->from($this->table)
					   ->whereEquals('langId', $this->getLangId());

		if ($published === true) {

			$db->andWhereEquals('published', '1')
			   ->orderBy('timestampAsked');

		} else {

			$db->orderBy('id')
			   ->desc();
		}

		return $db;
	}

	/**
	 * Queries for result-set
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to fetch only published records
	 * @return array
	 */
	private function getResults($page, $itemsPerPage, $published)
	{
		return $this->getSelectQuery($published)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches all published QA pairs filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, true);
	}

	/**
	 * Fetches all published QA pairs
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->getSelectQuery(true)
					->queryAll();
	}

	/**
	 * Fetches all QA pairs filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, false);
	}

	/**
	 * Fetches question data by QA's associated id
	 * 
	 * @param string $id QA id
	 * @return string
	 */
	public function fetchQuestionById($id)
	{
		return $this->db->select('question')
						->from($this->table)
						->whereEquals('id', $id)
						->query('question');
	}

	/**
	 * Fetches QA data by its associated id
	 * 
	 * @param string $id QA id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('id', $id)
						->query();
	}

	/**
	 * Updates QA data
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->db->update($this->table, array(

			'question'			=> $input['question'],
			'answer'			=> $input['answer'],
			'questioner'		=> $input['questioner'],
			'answerer'			=> $input['answerer'],
			'published'			=> $input['published'],
			'timestampAsked'	=> $input['timestampAsked'],
			'timestampAnswered'	=> $input['timestampAnswered'],

		))->whereEquals('id', $input['id'])
		  ->execute();
	}

	/**
	 * Adds QA pair
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(

			'langId'			=> $this->getLangId(),
			'question'			=> $input['question'],
			'answer'			=> $input['answer'],
			'questioner'		=> $input['questioner'],
			'answerer'			=> $input['answerer'],
			'published'			=> $input['published'],
			'timestampAsked'	=> $input['timestampAsked'],
			'timestampAnswered'	=> $input['timestampAnswered'],

		))->execute();
	}

	/**
	 * Updates published state by QA's associated id
	 * 
	 * @param string $id QA id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->db->update($this->table, array('published' => $published))
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Deletes QA pair by its associated id
	 * 
	 * @param string $id QA id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('id', $id)
						->execute();
	}
}
