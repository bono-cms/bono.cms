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
	protected $table = 'bono_module_announcement_categories';

	/**
	 * Fetches class id by associated class name
	 * 
	 * @param string $class
	 * @return string
	 */
	public function fetchIdByClass($class)
	{
		return $this->db->select('id')
						->from($this->table)
						->whereEquals('class', $class)
						->query('id');
	}

	/**
	 * Adds a category
	 * 
	 * @param string $name Category name
	 * @param string $class Category class
	 * @return boolean Depending on success
	 */
	public function insert($name, $class)
	{
		return $this->db->insert($this->table, array(

			'lang_id' => $this->getLangId(),
			'name'	 => $name,
			'class'  => $class

		))->execute();
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
		return $this->db->update($this->table, array(

			'name'	 => $name,
			'class'  => $class

		))->whereEquals('id', $id)
		  ->execute();
	}

	/**
	 * Delete a category by its associated id
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
	 * Fetches all categories
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
	 * Fetches category data by its associated id
	 * 
	 * @param string $id Category id
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
	 * Fetches category name by its associated id
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
}
