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
	public static function getTableName()
	{
		return 'bono_module_photoalbum_photos';
	}

	/**
	 * Returns shared select
	 * 
	 * @param boolean $published Whether to filter by published records only
	 * @param string $albumId Optionally can be filtered by album id
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published, $albumId = null)
	{
		$db = $this->db->select('*')
					   ->from(static::getTableName())
					   ->whereEquals('lang_id', $this->getLangId());

		if ($albumId !== null) {
			$db->andWhereEquals('album_id', $albumId);
		}

		if ($published === true) {
			$db->andWhereEquals('published', '1')
			   ->orderBy('order'); // @TODO: CASE WHEN `order` = 0 THEN id END
		} else {
			$db->orderBy('id')
			   ->desc();
		}

		return $db;
	}

	/**
	 * Queries for results
	 * 
	 * @param boolean $published Whether to sort only published records
	 * @param string $sort Column name to sort by
	 * @param string $albumId Optional album id
	 * @return array
	 */
	private function getResults($page, $itemsPerPage, $published, $albumId = null)
	{
		return $this->getSelectQuery($published, $albumId)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches a photo name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
	}

	/**
	 * Delete all photos associated with given album id
	 * 
	 * @param string $albumId
	 * @return boolean
	 */
	public function deleteAllByAlbumId($albumId)
	{
		return $this->deleteByColumn('album_id', $albumId);
	}

	/**
	 * Deletes a photo by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Adds a photo
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates a photo
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
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
	 * Count amount of records associated with category id
	 * 
	 * @param string $albumId
	 * @return integer
	 */
	public function countAllByAlbumId($albumId)
	{
		return $this->db->select()
						->count('id', 'count')
						->from(static::getTableName())
						->whereEquals('album_id', $albumId)
						->query('count');
	}

	/**
	 * Fetches photo ids by associated album id
	 * 
	 * @param string $albumId
	 * @return array
	 */
	public function fetchPhotoIdsByAlbumId($albumId)
	{
		return $this->db->select('id')
						->from(static::getTableName())
						->whereEquals('album_id', $albumId)
						->queryAll('id');
	}

	/**
	 * Fetches a photo by its associated id
	 * 
	 * @param string $id Photo's id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetches all photos
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->getSelectQuery(false)
					->queryAll();
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
		return $this->updateColumnByPk($id, 'published', $published);
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
		return $this->updateColumnByPk($id, 'order', $order);
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
		return $this->getResults($page, $itemsPerPage, true);
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
		return $this->getResults($page, $itemsPerPage, false);
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
		return $this->getResults($page, $itemsPerPage, false, $albumId);
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
		return $this->getResults($page, $itemsPerPage, true, $albumId);
	}

	/**
	 * Fetches all published photos associated with provided album id
	 * 
	 * @param string $albumId
	 * @return array
	 */
	public function fetchAllPublishedByAlbumId($albumId)
	{
		return $this->getSelectQuery(true, $albumId)
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
		return $this->getSelectQuery(false, $albumId)
					->queryAll();
	}
}
