<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Faq\Storage\FaqMapperInterface;

final class FaqMapper extends AbstractMapper implements FaqMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'Krystal_module_faq';

	/**
	 * Fetches question name by its associated id
	 * 
	 * @param string $id Faq id
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
	 * Updates column's value by associated faq id
	 * 
	 * @param string $id Faq id
	 * @param string $column Target column
	 * @param string $value New column's value
	 * @return boolean
	 */
	private function updateColumnById($id, $column, $value)
	{
		return $this->db->update($this->table, array($column => $value))
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Update published state by its associated faq id
	 * 
	 * @param integer $id Faq id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnById($id, 'published', $published);
	}

	/**
	 * Update an order by its associated faq id
	 * 
	 * @param string $id Faq id
	 * @param integer $order New sort order
	 * @return boolean
	 */
	public function updateOrderById($id, $order)
	{
		return $this->updateColumnById($id, 'order', $order);
	}

	/**
	 * Fetches all faqs filtered by pagination
	 * 
	 * @param integer $page Current page number
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
	 * Fetches all published faqs filtered by pagination
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
						->orderBy('order')
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}

	/**
	 * Fetches all published faqs
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('langId', $this->getLangId())
						->andWhereEquals('published', '1')
						->orderBy('order')
						->queryAll();
	}

	/**
	 * Count all records
	 * 
	 * @return integer
	 */
	private function countAll()
	{
		return $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('langId', $this->getLangId())
						->query('count');
	}

	/**
	 * Counts all published records
	 * 
	 * @return integer
	 */
	private function countAllPublished()
	{
		return $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('langId', $this->getLangId())
						->andWhereEquals('published', '1')
						->query('count');
	}

	/**
	 * Inserts a faq
	 * 
	 * @param array $data Faq data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(
			
			'langId'		=> $this->getLangId(),
			'order'			=> $data['order'],
			'question'		=> $data['question'],
			'answer'		=> $data['answer'],
			'published'		=> $data['published']
			
		))->execute();
	}

	/**
	 * Updates a fqq
	 * 
	 * @param array $data Faq data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(
			
			'order'			=> $data['order'],
			'question'		=> $data['question'],
			'answer'		=> $data['answer'],
			'published'		=> $data['published']
			
		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Deletes a faq by its associated id
	 * 
	 * @param string $id Faq's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Fetches faq data by its associated id
	 * 
	 * @param string $id Faq id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('id', $id)
						->query();
	}
}
