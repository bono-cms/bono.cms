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
	 * Fetches all published QA pairs filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		$this->paginator->setItemsPerPage($itemsPerPage)
						->setTotalAmount($this->countAllPublished())
						->setCurrentPage($page);

		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('langId', $this->getLangId())
						->orderBy('timestampAsked')
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
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
	 * Fetches all published QA pairs
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('langId', $this->getLangId())
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
		$this->paginator->setItemsPerPage($itemsPerPage)
						->setTotalAmount($this->countAll())
						->setCurrentPage($page);
		
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('langId', $this->getLangId())
						->orderBy('id')
						->desc()
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}

	/**
	 * Count all published QA pairs
	 * 
	 * @return integer
	 */
	private function countAllPublished()
	{
		return (int) $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('langId', $this->getLangId())
						->andWhereEquals('published', '1')
						->query('count');
	}

	/**
	 * Counts all QA pairs
	 * 
	 * @return integer
	 */
	private function countAll()
	{
		return (int) $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('langId', $this->getLangId())
						->query('count');
	}

	/**
	 * Updates QA data
	 * 
	 * @param array $data QA data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(
			
			'question'			=> $data['question'],
			'answer'			=> $data['answer'],
			'questioner'		=> $data['questioner'],
			'answerer'			=> $data['answerer'],
			'published'			=> $data['published'],
			'timestampAsked'	=> $data['timestampAsked'],
			'timestampAnswered'	=> $data['timestampAnswered'],
			
		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Inserts QA data
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(
			
			'langId'			=> $this->getLangId(),
			'question'			=> $data['question'],
			'answer'			=> $data['answer'],
			'questioner'		=> $data['questioner'],
			'answerer'			=> $data['answerer'],
			'published'			=> $data['published'],
			'timestampAsked'	=> $data['timestampAsked'],
			'timestampAnswered'	=> $data['timestampAnswered'],
			
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
