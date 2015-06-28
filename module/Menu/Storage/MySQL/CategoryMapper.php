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
use Menu\Storage\CategoryMapperInterface;

final class CategoryMapper extends AbstractMapper implements CategoryMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_menu_categories';
	}

	/**
	 * Fetches category class by its associated id
	 * 
	 * @param string $id Category id
	 * @return string
	 */
	public function fetchClassById($id)
	{
		return $this->findColumnByPk($id, 'class');
	}

	/**
	 * Fetch maximal allowed depth of nested level
	 * 
	 * @param string $id Category id
	 * @return integer
	 */
	public function fetchMaxDepthById($id)
	{
		return $this->findColumnByPk($id, 'max_depth');
	}

	/**
	 * Fetches category's name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
	}

	/**
	 * Fetches a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetches category's name by its associated class name
	 * 
	 * @param string $class Category's name
	 * @return string
	 */
	public function fetchNameByClass($class)
	{
		return $this->db->select('name')
						->from(static::getTableName())
						->whereEquals('class', $class)
						->andWhereEquals('lang_id', $this->getLangId())
						->query('name');
	}

	/**
	 * Fetch unique classes
	 * 
	 * @return array
	 */
	public function fetchClasses()
	{
		return $this->db->select('class', true)
						->from(static::getTableName())
						->queryAll('class');
	}

	/**
	 * Fetches an id by order's sort type
	 * Filtered by a language
	 * 
	 * @param string $sort Either ASC or DESC
	 * @return integer
	 */
	private function fetchId($sort)
	{
		return $this->db->select('id')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->sort($sort)
						->query('id');
	}

	/**
	 * Fetches the first id
	 * 
	 * @return string
	 */
	public function fetchFirstId()
	{
		return $this->fetchId('ASC');
	}

	/**
	 * Fetches the last id
	 * 
	 * @return integer
	 */
	public function fetchLastId()
	{
		return $this->fetchId('DESC');
	}

	/**
	 * Fetches an id by its associated class's name
	 * 
	 * @param string $class Class name
	 * @return string Class id
	 */
	public function fetchIdByClass($class)
	{
		return $this->db->select('id')
						->from(static::getTableName())
						->whereEquals('class', $class)
						->andWhereEquals('lang_id', $this->getLangId())
						->query('id');
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

	/**
	 * Deletes a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Inserts a category
	 * 
	 * @param array $data Category data
	 * @return boolean Depending on success
	 */
	public function insert(array $data)
	{
		return $this->db->insert(static::getTableName(), array(
			
			'lang_id'	=> $this->getLangId(),
			'name'		=> $data['name'],
			'max_depth'	=> $data['max_depth'],
			'class'		=> $data['class']
			
		))->execute();
	}

	/**
	 * Updates a category
	 * 
	 * @param array $data Category data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update(static::getTableName(), array(
			
			'name' => $data['name'], 
			'max_depth' => $data['max_depth'], 
			'class' => $data['class']
			
		))->whereEquals('id', $data['id'])
		  ->execute();
	}
}
