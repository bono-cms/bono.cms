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
			   ->orderBy('timestamp');
		} else {
			$db->orderBy('id');
		}

		$db->desc();
		return $db;
	}

	/**
	 * Queries for a result
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to sort only published records
	 * @return array
	 */
	private function getResults($page, $itemsPerPage, $published)
	{
		return $this->getSelectQuery($published)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

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
	 * Adds a review
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(

			'langId'		=> $this->getLangId(),
			'timestamp'		=> $input['timestamp'],
			'ip'			=> $input['ip'],
			'published'		=> $input['published'],
			'name'			=> $input['name'],
			'email'			=> $input['email'],
			'content'		=> $input['content'],

		))->execute();
	}

	/**
	 * Updates a review
	 * 
	 * @param array $input Review data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->db->update($this->table, array(

			'timestamp' => $input['timestamp'],
			'published'	=> $input['published'],
			'name'     	=> $input['name'],
			'email'		=> $input['email'],
			'content'	=> $input['content'],

		))->whereEquals('id', $input['id'])
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
		return $this->getResults($page, $itemsPerPage, false);
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
		return $this->getResults($page, $itemsPerPage, true);
	}

	/**
	 * Fetches review data by its associated id
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
	 * Deletes a review by its associated id
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
}
