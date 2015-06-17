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
use Photogallery\Storage\PhotoMapperInterface;

final class PhotoMapper extends AbstractMapper implements PhotoMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_photoalbum_photos';

	/**
	 * Fetches a photo name by its associated id
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
	 * Delete all photos associated with given album id
	 * 
	 * @param string $albumId
	 * @return boolean
	 */
	public function deleteAllByAlbumId($albumId)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('album_id', $albumId)
						->execute();
	}

	/**
	 * Adds a photo
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(
			
			'lang_id'		=> $this->getLangId(),
			'album_id'		=> $data['albumId'],
			'name'			=> $data['name'],
			'photo'			=> $data['photo'],
			'order'			=> $data['order'],
			'description'	=> $data['description'],
			'published'		=> $data['published'],
			
		))->execute();
	}

	/**
	 * Updates a photo
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(

			'album_id'		=> $data['albumId'],
			'name'			=> $data['name'],
			'photo'			=> $data['photo'],
			'order'			=> $data['order'],
			'description'	=> $data['description'],
			'published'		=> $data['published'],

		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Deletes a photo by its associated id
	 * 
	 * @param string $id Photo's id
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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('lang_id', $this->getLangId())
						->queryAll();
	}

	/**
	 * Fetches all published records by page
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublihedByPage($page, $itemsPerPage)
	{
		$this->paginator->setItemsPerPage($itemsPerPage)
						->setTotalAmount($this->countAllPublished())
						->setCurrentPage($page);
		
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('lang_id', $this->getLangId())
						->orderBy('order')
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}

	/**
	 * Count all published photos
	 * 
	 * @return integer
	 */
	private function countAllPublished()
	{
		return $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('published', '1')
						->query('count');
	}

	/**
	 * Count all photos
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

	/**
	 * Count amount of records associated with category id
	 * 
	 * @param string $albumId
	 * @return integer
	 */
	public function countAllByAlbumId($albumId)
	{
		return $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('album_id', $albumId)
						->query('count');
	}

	/**
	 * Count amount of records associated with category id
	 * 
	 * @param string $albumId
	 * @return integer
	 */
	private function countAllPublishedByAlbumId($albumId)
	{
		return $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('album_id', $albumId)
						->andWhereEquals('published', '1')
						->query('count');
	}

	/**
	 * Fetches a photo by its associated id
	 * 
	 * @param string $id Photo's id
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
	 * Fetches all photos
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
	 * Updates row columns by matched id
	 * 
	 * @param string $id
	 * @param string $column
	 * @param string $value
	 * @return boolean
	 */
	private function updateRowById($id, $column, $value)
	{
		return $this->db->update($this->table, array($column => $value))
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Updates published state by its associated ids
	 * 
	 * @param string $id
	 * @param string $published
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateRowById($id, 'published', $published);
	}

	/**
	 * Updates an order by its associated id
	 * 
	 * @param string $id
	 * @param integer $order
	 * @return boolean
	 */
	public function updateOrderById($id, $order)
	{
		return $this->updateRowById($id, 'order', $order);
	}

	/**
	 * Fetch photos by associated category's id
	 * 
	 * @param string $categoryId
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByAlbumIdAndPage($albumId, $page, $itemsPerPage)
	{
		$this->paginator->setItemsPerPage($itemsPerPage)
						->setTotalAmount($this->countAllByAlbumId($albumId))
						->setCurrentPage($page);
		
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('album_id', $albumId)
						->orderBy('id')
						->desc()
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}

	/**
	 * Fetches only published records associated with given album id
	 * 
	 * @param string $albumId
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllPublishedByAlbumIdAndPage($albumId, $page, $itemsPerPage)
	{
		$this->paginator->setItemsPerPage($itemsPerPage)
						->setTotalAmount($this->countAllPublishedByAlbumId($albumId))
						->setCurrentPage($page);
		
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('album_id', $albumId)
						->andWhereEquals('published', '1')
						//, CASE WHEN `order` = 0 THEN id END
						->orderBy('order')
						->desc()
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}

	/**
	 * Fetch all records filter by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		$this->paginator->tweak($this->countAll(), $itemsPerPage, $page);

		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}

	/**
	 * Fetches all photos filtered by album id
	 * 
	 * @param string $albumId
	 * @return array
	 */
	public function fetchAllByAlbumId($albumId)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('album_id', $albumId)
						->queryAll();
	}
}
