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
	public static function getTableName()
	{
		return 'bono_module_shop_categories';
	}

	/**
	 * Builds shared select
	 * 
	 * @param boolean $front
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($front)
	{
		$db = $this->db->select('*')
					   ->from(static::getTableName())
					   ->whereEquals('lang_id', $this->getLangId());

		if ($front === true) {
			$db->orderBy('order');
		} else {
			$db->orderBy('id')
			   ->desc();
		}

		return $db;
	}

	/**
	 * Queries for a result
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $front
	 * @return array
	 */
	private function getResults($page, $itemsPerPage, $front)
	{
		return $this->getSelectQuery($front)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Adds a category
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert(static::getTableName(), array(
			
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
		return $this->db->update(static::getTableName(), array(
			
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
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Deletes a category by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Deletes a category by its associated parent id
	 * 
	 * @param string $parentId Category parent id
	 * @return boolean
	 */
	public function deleteByParentId($parentId)
	{
		return $this->deleteByColumn('parent_id', $parentId);
	}

	/**
	 * Fetches breadcrumb's data
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	public function fetchBcData()
	{
		return $this->db->select(array('title', 'web_page_id', 'lang_id', 'parent_id', 'id'))
						->from(static::getTableName())
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
		return $this->findColumnByPk($id, 'title');
	}

	/**
	 * Fetches all categories
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->queryAll();
	}

	/**
	 * Fetches all categories filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByIdAndPage($page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, false);
	}

	/**
	 * Fetches all published categories by associated id and filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByIdAndPage($page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, true);
	}
}
