<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Slider\Storage\ImageMapperInterface;
use Krystal\Db\Sql\RawSqlFragment;

final class ImageMapper extends AbstractMapper implements ImageMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_slider_images';
	}
	
	/**
	 * Returns shared select
	 * 
	 * @param boolean $published
	 * @param string $categoryId Optional category id
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published, $categoryId = null)
	{
		// Build first shared fragment
		$db = $this->db->select('*')
					   ->from(static::getTableName())
					   ->whereEquals('lang_id', $this->getLangId());

		if ($categoryId !== null) {
			$db->andWhereEquals('category_id', $categoryId);
		}

		if ($published === true) {
			$db->andWhereEquals('published', '1')
			   ->orderBy(new RawSqlFragment('`order`, CASE WHEN `order` = 0 THEN `id` END DESC'));
		} else {
			$db->orderBy('id')
			   ->desc();
		}

		return $db;
	}

	/**
	 * Queries for results
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to sort only published records
	 * @param string $categoryId Optional category id
	 * @return array
	 */
	private function getResults($page, $itemsPerPage, $published, $categoryId = null)
	{
		return $this->getSelectQuery($published, $categoryId)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches image's name by its associated id
	 * 
	 * @param string $id Image id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
	}

	/**
	 * Updates image's published state by its associated id
	 * 
	 * @param integer $id Image id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnByPk($id, 'published', $published);
	}

	/**
	 * Updates image's order by its associated id
	 * 
	 * @param string $id Image id
	 * @param string $order New order
	 * @return boolean
	 */
	public function updateOrderById($id, $order)
	{
		return $this->updateColumnByPk($id, 'order', $order);
	}

	/**
	 * Fetches all associated image ids with their associated category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchIdsByCategoryId($categoryId)
	{
		return $this->db->select('id')
						->from(static::getTableName())
						->whereEquals('category_id', $categoryId)
						->queryAll('id');
	}

	/**
	 * Counts all images associated with given category id
	 * Public intentionally
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllByCategoryId($categoryId)
	{
		return $this->db->select()
						->count('id', 'count')
						->from(static::getTableName())
						->whereEquals('category_id', $categoryId)
						->query('count');
	}

	/**
	 * Fetches image id by its associated category id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchCategoryIdById($id)
	{
		return $this->findColumnByPk($id, 'category_id');
	}

	/**
	 * Fetches all images filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, false);
	}

	/**
	 * Fetches all published images filtered by pagination
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
	 * Fetches all published images associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId)
	{
		return $this->getSelectQuery(true, $categoryId)
					->queryAll();
	}

	/**
	 * Fetches all published images by category id and filtered by pagination
	 * 
	 * @param integer $categoryId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByCategoryIdAndPage($categoryId, $page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, true, $categoryId);
	}

	/**
	 * Fetches all images by category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryAndPage($categoryId, $page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, false, $categoryId);
	}

	/**
	 * Updates an image
	 * 
	 * @param array $data Image data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->persist($data);
	}

	/**
	 * Inserts an image
	 * 
	 * @param array $data Image data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->persist($this->getWithLang($data));
	}

	/**
	 * Fetches an image by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Deletes an image by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}
}
