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

final class ImageMapper extends AbstractMapper implements ImageMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_slider_images';

	/**
	 * Fetches image's name by its associated id
	 * 
	 * @param string $id Image id
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
	 * Updates image column's value by its id
	 * 
	 * @param string $id Image id
	 * @param string $column Target column
	 * @param string $value New value
	 * @return boolean
	 */
	private function updateColumnById($id, $column, $value)
	{
		return $this->db->update($this->table, array($column => $value))
						->whereEquals('id', $id)
						->execute();
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
		return $this->updateColumnById($id, 'published', $published);
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
		return $this->updateColumnById($id, 'order', $order);
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
						->from($this->table)
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
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->query('count');
	}

	/**
	 * Fetches image id by its associated category id
	 * 
	 * @param string $id Image id
	 * @return string
	 */
	public function fetchCategoryIdById($id)
	{
		return $this->db->select('category_id')
						->from($this->table)
						->whereEquals('id', $id)
						->query('category_id');
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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('published', '1')
						->orderBy('order') //@TODO
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches all published images associated with given category id
	 * 
	 * @param string $categoryId Category id
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->andWhereEquals('published', '1')
						// @TODO: CASE WHEN `order` = 0 THEN `id` END
						->orderBy('order')
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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->andWhereEquals('published', '1')
						->orderBy('order')
						->paginate($page, $itemsPerPage)
						->queryAll();
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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Updates an image
	 * 
	 * @param array $data Image data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(
			
			'category_id'	=> $data['category_id'],
			'name'			=> $data['name'],
			'description'	=> $data['description'],
			'order'			=> $data['order'],
			'published'		=> $data['published'],
			'link' 			=> $data['link'],
			'image'			=> $data['image']
			
		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Inserts an image
	 * 
	 * @param array $data Image data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(
			
			'lang_id'		=> $this->getLangId(),
			'category_id'	=> $data['category_id'],
			'name'			=> $data['name'],
			'description'	=> $data['description'],
			'order'			=> $data['order'],
			'published'		=> $data['published'],
			'link' 			=> $data['link'],
			'image'			=> $data['image']
			
		))->execute();
	}

	/**
	 * Fetches an image by its associated id
	 * 
	 * @param string $id Image id
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
	 * Deletes an image by its associated id
	 * 
	 * @param string $id Image id
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
