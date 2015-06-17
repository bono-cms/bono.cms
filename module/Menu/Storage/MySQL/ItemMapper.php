<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Menu\Storage\ItemMapperInterface;

final class ItemMapper extends AbstractMapper implements ItemMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_menu_items';

	/**
	 * Fetches category id by its associated web page id
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function fetchCategoryIdByWebPageId($webPageId)
	{
		return $this->db->select('category_id')
						->from($this->table)
						->whereEquals('web_page_id', $webPageId)
						->query('category_id');
	}

	/**
	 * Fetches all items associated with given web page id
	 * 
	 * @param string $webPageId
	 * @return array
	 */
	public function fetchAllByWebPageId($webPageId)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('web_page_id', $webPageId)
						->queryAll();
	}

	/**
	 * Saves "drag and drop" move
	 * 
	 * @param string $id
	 * @param string $parentId
	 * @param integer $range
	 * @return boolean
	 */
	public function save($id, $parentId, $range)
	{
		return $this->db->update($this->table, array('range' => $range, 'parent_id' => $parentId))
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Fetches item's name by its associated id
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
	 * Fetches all items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllByCategoryId($categoryId)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('category_id', $categoryId)
						->orderBy('range')
						->queryAll();
	}

	/**
	 * Fetches all published items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('category_id', $categoryId)
						->andWhereEquals('published', '1')
						->orderBy('range')
						->queryAll();
	}

	/**
	 * Deletes an item by its associated id
	 * 
	 * @param string $id
	 * @return boolean Depending on success
	 */
	public function deleteById($id)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Deletes all items associated with given category id
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
	 * Delete all items associated with given parent id
	 * 
	 * @param string $parentId
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
	 * Deletes all items by associated web page id
	 * 
	 * @param string $webPageId
	 * @return boolean
	 */
	public function deleteAllByWebPageId($webPageId)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('web_page_id', $webPageId)
						->execute();
	}

	/**
	 * Fetches an item by its associated id
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
	 * Fetches all items
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
	 * Inserts an item
	 * 
	 * @param array $data
	 * @return boolean Depending on success
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(

			'lang_id'		=> $this->getLangId(),
			'parent_id'		=> $data['parent_id'],
			'category_id'	=> $data['category_id'],
			'web_page_id'	=> $data['web_page_id'],
			'name'			=> $data['name'],
			'link'			=> $data['link'],
			'has_link'		=> $data['has_link'],
			'hint'			=> $data['hint'],
			'published'		=> $data['published'],
			'open_in_new_window' => $data['open_in_new_window']
			
		))->execute();
	}

	/**
	 * Updates an item
	 * 
	 * @param array $data
	 * @return boolean Depending on success
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(

			'category_id'	=> $data['category_id'],
			'web_page_id'   => $data['web_page_id'],
			'name'			=> $data['name'],
			'link'			=> $data['link'],
			'has_link'		=> $data['has_link'],
			'hint'			=> $data['hint'],
			'published'		=> $data['published'],
			'open_in_new_window' => $data['open_in_new_window']

		))->whereEquals('id', $data['id'])
		  ->execute();
	}
}
