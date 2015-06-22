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
	protected $table = 'bono_module_faq';

	/**
	 * Fetches question name by its associated id
	 * 
	 * @param string $id FAQ id
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
	 * Updates column's value by associated FAQ id
	 * 
	 * @param string $id FAQ id
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
	 * Fetches all FAQs filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('langId', $this->getLangId())
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches all published FAQs filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('langId', $this->getLangId())
						->orderBy('order')
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches all published FAQs
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
	 * Adds new FAQ
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(

			'langId'		=> $this->getLangId(),
			'order'			=> $input['order'],
			'question'		=> $input['question'],
			'answer'		=> $input['answer'],
			'published'		=> $input['published']

		))->execute();
	}

	/**
	 * Updates a FAQ
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->db->update($this->table, array(

			'order'			=> $input['order'],
			'question'		=> $input['question'],
			'answer'		=> $input['answer'],
			'published'		=> $input['published']

		))->whereEquals('id', $input['id'])
		  ->execute();
	}

	/**
	 * Deletes a FAQ by its associated id
	 * 
	 * @param string $id FAQ's id
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
	 * Fetches FAQ's data by its associated id
	 * 
	 * @param string $id FAQ's id
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
