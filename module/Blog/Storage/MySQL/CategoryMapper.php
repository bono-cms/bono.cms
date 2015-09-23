<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Blog\Storage\CategoryMapperInterface;

final class CategoryMapper extends AbstractMapper implements CategoryMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_blog_categories';
	}

	/**
	 * Fetches as a list
	 * 
	 * @return array
	 */
	public function fetchList()
	{
		return $this->db->select(array('id', 'title'))
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->queryAll();
	}

	/**
	 * Fetches data for breadcrumbs
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchBcDataById($id)
	{
		return $this->db->select(array('title', 'web_page_id'))
						->from(static::getTableName())
						->whereEquals('id', $id)
						->query();
	}

	/**
	 * Fetches all basic data about categories
	 * 
	 * @return array
	 */
	public function fetchAllBasic()
	{
		return $this->db->select(array('id', 'lang_id', 'web_page_id', 'title'))
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('order')
						->queryAll();
	}

	/**
	 * Inserts a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithlang($input));
	}

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}

	/**
	 * Deletes a category by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Fetches category name by its associated id
	 * 
	 * @param string $id Category id
	 * @return string
	 */
	public function fetchTitleById($id)
	{
		return $this->findColumnByPk($id, 'title');
	}

	/**
	 * Fetches category data by its associated id
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
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
						->orderBy('id')
						->desc()
						->queryAll();
	}
}
