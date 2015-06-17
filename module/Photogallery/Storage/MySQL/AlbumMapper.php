<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Photogallery\Storage\AlbumMapperInterface;

final class AlbumMapper extends AbstractMapper implements AlbumMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_photoalbum_albums';

	/**
	 * Fetches album name by its associated id
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
	 * Fetches all albums
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->queryAll();
	}

	/**
	 * Inserts a record
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(

			'lang_id'			=> $this->getLangId(),
			'parent_id' 		=> $data['parent_id'],
			'web_page_id'		=> '',
			'title'				=> $data['title'],
			'name'				=> $data['name'],
			'description'		=> $data['description'],
			'order'				=> $data['order'],
			'keywords'			=> $data['keywords'],
			'meta_description'	=> $data['meta_description'],
			'seo'				=> $data['seo']

		))->execute();
	}

	/**
	 * Updates an album
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(
			
			'parent_id' 	=> $data['parent_id'],
			'title'     	=> $data['title'],
			'name'			=> $data['name'],
			'description'	=> $data['description'],
			'order'			=> $data['order'],
			'keywords'		=> $data['keywords'],
			'meta_description' => $data['meta_description'],
			'seo'			=> $data['seo']
			
		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Deletes an album by its associated id
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
	 * Deletes all albums children by associated parent id
	 * 
	 * @pamra integer $parentId
	 * @return boolean
	 */
	public function deleteAllByParentId($parentId)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('parent_id', $parentId)
						->execute();
	}

	/**
	 * Fetches a record by its id
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
	 * Fetch all records filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		$this->paginator->setTotalAmount($this->countAll())
						->setItemsPerPage($itemsPerPage)
						->setCurrentPage($page);
		
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
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
						->whereEquals('lang_id', $this->getLangId())
						->query('count');
	}
}
