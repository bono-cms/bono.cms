<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Reviews\Storage\ReviewsMapperInterface;

final class ReviewsMapper extends AbstractMapper implements ReviewsMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_reviews';

	/**
	 * Fetches review author's name by associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->db->select('name')
						->from($this->table)
						->whereEquals('id', $id)
						->query('name');
	}

	/**
	 * Updates review published state by its associated id
	 * 
	 * @param string $id Review id
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
	 * Inserts a review
	 * 
	 * @param array $data Review data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(

			'langId'		=> $this->getLangId(),
			'timestamp'		=> $data['timestamp'],
			'ip'			=> $data['ip'],
			'published'		=> $data['published'],
			'name'			=> $data['name'],
			'email'			=> $data['email'],
			'content'		=> $data['content'],

		))->execute();
	}

	/**
	 * Updates a review
	 * 
	 * @param array $data Review data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(

			'timestamp' => $data['timestamp'],
			'published'	=> $data['published'],
			'name'     	=> $data['name'],
			'email'		=> $data['email'],
			'content'	=> $data['content'],

		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Fetches all published reviews filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		$this->paginator->setTotalAmount($this->countAll())
						->setItemsPerPage($itemsPerPage)
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
	 * Fetches all published reviews filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		$this->paginator->setTotalAmount($this->countAllPublished())
						->setItemsPerPage($itemsPerPage)
						->setCurrentPage($page);
		
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('langId', $this->getLangId())
						->andWhereEquals('published', '1')
						->orderBy('timestamp')
						->desc()
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}

	/**
	 * Counts all published reviews
	 * 
	 * @return integer
	 */
	private function countAllPublished()
	{
		return (int) $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('langId', $this->getLangId())
						->query('count');
	}

	/**
	 * Counts all reviews
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
	 * Fetches review data by its associated id
	 * 
	 * @param string $id Review id
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
	 * Deletes a review by its associated id
	 * 
	 * @param string $id Review id
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
