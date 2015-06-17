<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Shop\Storage\CategoryMapperInterface;

final class CategoryMapper extends AbstractMapper implements CategoryMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_shop_categories';

	/**
	 * Fetches breadcrumb's data
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	public function fetchBcData()
	{
		return $this->db->select(array('title', 'web_page_id', 'lang_id', 'parent_id', 'id'))
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->queryAll();
	}

	/**
	 * Fetches category's title by its associated id
	 * 
	 * @param string $id Category id
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
	 * Fetches all categories
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->queryALl();
	}

	/**
	 * Adds a category
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(
			
			'lang_id'			=> $this->getLangId(),
			'parent_id'			=> $data['parent_id'],
			'web_page_id'		=> $data['web_page_id'],
			'title'				=> $data['title'],
			'description'		=> $data['description'],
			'order'				=> $data['order'],
			'seo'				=> $data['seo'],
			'keywords'			=> $data['keywords'],
			'meta_description'	=> $data['meta_description'],
			'cover'				=> $data['cover'],
			
		))->execute();
	}

	/**
	 * Updates a category
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(
			
			'parent_id'		=> $data['parent_id'],
			'title'			=> $data['title'],
			'description'	=> $data['description'],
			'order'			=> $data['order'],
			'seo'			=> $data['seo'],
			'keywords'		=> $data['keywords'],
			'meta_description' => $data['meta_description'],
			'cover'        	=> $data['cover'],
			
		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Fetches category's data by its associated id
	 * 
	 * @param string $id Category's id
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
	 * Deletes a category by its associated id
	 * 
	 * @param string $id Category id
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
	 * Deletes a category by its associated parent id
	 * 
	 * @param string $parentId Category parent id
	 * @return boolean
	 */
	public function deleteByParentId($parentId)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('parent_id', $parentId)
						->execute();
	}

	/**
	 * Fetches all categories filtered by pagination
	 * 
	 * @param string $id Category's id
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByIdAndPage($id, $page, $itemsPerPage)
	{
		$this->paginator->setItemsPerPage($itemsPerPage)
						->setTotalAmount($this->countAllById($id))
						->setCurrentPage($page);

		return $this->db->select('*')
						->from($this->table)
						->whereEquals('id', $id)
						->orderBy('id')
						->desc()
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}

	/**
	 * Fetches all published categories by associated id and filtered by pagination
	 * 
	 * @param string $id Category id
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByIdAndPage($id, $page, $itemsPerPage)
	{
		$this->paginator->setItemsPerPage($itemsPerPage)
						->setTotalAmount($this->countAllPublishedById($id))
						->setCurrentPage($page);

		return $this->db->select('*')
						->from($this->table)
						->whereEquals('id', $id)
						->andWhereEquals('published', '1')
						->orderBy('order')
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}

	/**
	 * Counts all categories by associated id
	 * 
	 * @param string $id Category id
	 * @return integer
	 */
	private function countAllById($id)
	{
		return (int) $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('id', $id)
						->query('count');
	}
}
