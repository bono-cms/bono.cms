<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Announcement\Storage\AnnounceMapperInterface;

final class AnnounceMapper extends AbstractMapper implements AnnounceMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_announcement_announces';

	/**
	 * Fetches all published announces associated with provided category id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($id)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('category_id', $id)
						->queryAll();
	}

	/**
	 * Fetches announce title by its associated id
	 * 
	 * @param string $id Announce id
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
	 * Deletes all announces associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteAllByCategoryId($categoryId)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->execute();
	}

	/**
	 * Deletes an announce by its associated id
	 * 
	 * @param string $id Announce id
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
	 * Adds an announce
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(
			
			'lang_id'	 	=> $this->getLangId(),
			'category_id' 	=> $input['categoryId'],
			'web_page_id'	=> $input['webPageId'],
			'title'			=> $input['title'],
			'name'		 	=> $input['name'],
			'intro'		 	=> $input['intro'],
			'full'		 	=> $input['full'],
			'order'		 	=> $input['order'],
			'published'  	=> $input['published'],
			'seo' 			=> $input['seo'],
			'slug'		 	=> $input['slug'],
			'keywords'	 	=> $input['keywords'],
			'meta_description' => $input['metaDescription'],
			'cover'		 	=> $input['cover']

		))->execute();
	}

	/**
	 * Updates an announce
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function update(array $input)
	{
		return $this->db->update($this->table, array(

			'category_id' 	=> $input['categoryId'],
			'title'			=> $input['title'],
			'name'		 	=> $input['name'],
			'intro'		 	=> $input['intro'],
			'full'		 	=> $input['full'],
			'order'		 	=> $input['order'],
			'published'  	=> $input['published'],
			'seo' 			=> $input['seo'],
			'slug'		 	=> $input['slug'],
			'keywords'	 	=> $input['keywords'],
			'meta_description' => $input['metaDescription'],
			'cover'		 	=> $input['cover']

		))->whereEquals('id', $input['id'])
		  ->execute();
	}

	/**
	 * Fetches announce data by its associated id
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
	 * Updates a column's value by its associated id
	 * 
	 * @param string $id Target id
	 * @param string $column Target column
	 * @param string $value
	 * @return boolean
	 */
	private function updateRowColumnById($id, $column, $value)
	{
		return $this->db->update($this->table, array($column => $value))
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Updates seo value
	 * 
	 * @param string $id
	 * @param string $seo Either 0 or 1
	 * @return boolean
	 */
	public function updateSeoById($id, $seo)
	{
		return $this->updateRowColumnById($id, 'seo', $seo);
	}

	/**
	 * Updates published value
	 * 
	 * @param string $id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateRowColumnById($id, 'published', $published);
	}

	/**
	 * Fetches all announces filtered by pagination
	 * 
	 * @param integer $page Current page number
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
	 * Fetches all published announces
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('lang_id', $this->getLangId())
						->queryAll();
	}

	/**
	 * Fetches all published announces filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('lang_id', $this->getLangId())
						->orderBy('order')
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches all published announces filter by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}
}
