<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Advice\Storage\AdviceMapperInterface;

final class AdviceMapper extends AbstractMapper implements AdviceMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_advice';

	/**
	 * Fetches advice's title by its associated id
	 * 
	 * @param string $id Advice id
	 * @return string
	 */
	public function fetchTitleById($id)
	{
		return $this->db->select('title')
						->from($this->table)
						->whereEquals('id', $id)
						->query('title');
	}

	/**
	 * Updates published state by advice's associated id
	 * 
	 * @param string $id Advice id
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
	 * Fetches a random published advice
	 * 
	 * @return array
	 */
	public function fetchRandom()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('published', '1')
						->orderBy()
						->rand()
						->limit(1)
						->query();
	}

	/**
	 * Fetches all advices filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
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

	/**
	 * Fetches all published advice filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
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
	 * Fetches all advices
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->queryAll();
	}

	/**
	 * Fetches an advice by its associated id
	 * 
	 * @param string $id Advice id
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
	 * Deletes an advice by its associated id
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
	 * Inserts an advice
	 * 
	 * @param string $title
	 * @param string $content
	 * @param string $published
	 * @return boolean
	 */
	public function insert($title, $content, $published)
	{
		return $this->db->insert($this->table, array(
			
			'lang_id'	=> $this->getLangId(),
			'title'		=> $title,
			'content'	=> $content,
			'published'	=> $published,
			
		))->execute();
	}

	/**
	 * Updates an advice
	 * 
	 * @param string $id
	 * @param string $title
	 * @param string $content
	 * @param string $published
	 * @return boolean Depending on success
	 */
	public function update($id, $title, $content, $published)
	{
		return $this->db->update($this->table, array(
			
			'title'	=> $title,
			'content'	=> $content,
			'published' => $published,
			
		))->whereEquals('id', $id)
		  ->execute();
	}
}
