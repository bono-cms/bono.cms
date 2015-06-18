<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Team\Storage\TeamMapperInterface;

final class TeamMapper extends AbstractMapper implements TeamMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_team';

	/**
	 * Fetches name by associated id
	 * 
	 * @param string $id Member's id
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
	 * Updates an order by its associated id
	 * 
	 * @param string $id
	 * @param string $order
	 * @return boolean
	 */
	public function updateOrderById($id, $order)
	{
		return $this->updateColumnById('order', $order, $id);
	}

	/**
	 * Update published state by its associated id
	 * 
	 * @param string $id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnById('published', $published, $id);
	}

	/**
	 * Updates a column by its associated id
	 * 
	 * @param string $column
	 * @param string $value
	 * @param string $id
	 * @return boolean
	 */
	private function updateColumnById($id, $column, $value)
	{
		return $this->db->update($this->table, array($column => $value))
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Updates a record
	 * 
	 * @param stdclass $container
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(

			'name'			=> $data['name'],
			'description'	=> $data['description'],
			'photo'			=> $data['photo'],
			'published'		=> $data['published'],
			'order'			=> $data['order'],

		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Adds a member
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(

			'lang_id'		=> $this->getLangId(),
			'name'			=> $data['name'],
			'description'	=> $data['description'],
			'photo'			=> $data['photo'],
			'published'		=> $data['published'],
			'order'			=> $data['order'],

		))->execute();
	}

	/**
	 * Fetches a record by its associated id
	 * 
	 * @param string $id
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
	 * Deletes a record by its associated id
	 * 
	 * @param string $id
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
	 * Fetches all published records
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		//@TODO
	}

	/**
	 * Fetches all published records
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('published', '1')
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches all records
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		//@TODO
	}

	/**
	 * Fetches all members filtered by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}
}
