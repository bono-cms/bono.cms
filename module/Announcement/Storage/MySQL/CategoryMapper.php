<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Storage\MySQL;

use Announcement\Storage\CategoryMapperInterface;
use Cms\Storage\MySQL\AbstractMapper;

final class CategoryMapper extends AbstractMapper implements CategoryMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_announcement_categories';
	}

	/**
	 * Fetches as a list
	 * 
	 * @return array
	 */
	public function fetchList()
	{
		return $this->db->select(array('id', 'name'))
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->queryAll();
	}

	/**
	 * Adds a category
	 * 
	 * @param string $name Category name
	 * @param string $class Category class
	 * @return boolean
	 */
	public function insert($name, $class)
	{
		$data = $this->getWithLang(array(
			'name'	 => $name,
			'class'  => $class
		));

		return $this->persist($data);
	}

	/**
	 * Updates a category
	 * 
	 * @param string $id Category id
	 * @param string $name New category name
	 * @param string $class New category class
	 * @return boolean
	 */
	public function update($id, $name, $class)
	{
		return $this->persist(array(
			'name' => $name,
			'class' => $class,
			'id' => $id
		));
	}

	/**
	 * Delete a category by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
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
	 * Fetches class id by associated class name
	 * 
	 * @param string $class
	 * @return string
	 */
	public function fetchIdByClass($class)
	{
		return $this->fetchOneColumn('id', 'class', $class);
	}

	/**
	 * Fetches category name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
	}
}
