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
	public static function getTableName()
	{
		return 'bono_module_team';
	}

	/**
	 * Fetches a name by associated id
	 * 
	 * @param string $id Member's id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
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
		return $this->updateColumnByPk('order', $order, $id);
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
		return $this->updateColumnByPk('published', $published, $id);
	}

	/**
	 * Updates a member
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->db->update(static::getTableName(), array(

			'name'			=> $input['name'],
			'description'	=> $input['description'],
			'photo'			=> $input['photo'],
			'published'		=> $input['published'],
			'order'			=> $input['order'],

		))->whereEquals('id', $input['id'])
		  ->execute();
	}

	/**
	 * Adds a member
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert(static::getTableName(), array(

			'lang_id'		=> $this->getLangId(),
			'name'			=> $input['name'],
			'description'	=> $input['description'],
			'photo'			=> $input['photo'],
			'published'		=> $input['published'],
			'order'			=> $input['order'],

		))->execute();
	}

	/**
	 * Deletes a record by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Returns shared select query
	 * 
	 * @param boolean $published
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published)
	{
		// Build first fragment
		$db = $this->db->select('*')
					   ->from(static::getTableName())
					   ->whereEquals('lang_id', $this->getLangId());

		if ($published === true) {

			$db->andWhereEquals('published', '1')
			   ->orderBy('order');

		} else {

			$db->orderBy('id')
			   ->desc();
		}

		return $db;
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
		return $this->getSelectQuery(true)
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
		return $this->getSelectQuery(false)
					->queryAll();
	}

	/**
	 * Fetches all published records
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->getSelectQuery(true)
					->queryAll();
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
		return $this->getSelectQuery(false)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches a record by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}
}
