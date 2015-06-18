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
use Slider\Storage\CategoryMapperInterface;

final class CategoryMapper extends AbstractMapper implements CategoryMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_slider_category';

	/**
	 * Fetches category's id by its associated class name
	 * 
	 * @param string $class Category's class name
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
	 * Fetches category's name by its associated id
	 * 
	 * @param string $id Category id
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
	 * Inserts a category
	 * 
	 * @param array $data Category data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(

			'lang_id' => $this->getLangId(),
			'name'    => $data['name'],
			'class'	  => $data['class'],
			'width'   => $data['width'],
			'height'  => $data['height']

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
		return $this->db->update($this->table, array(
			
			'name'		=> $data['name'],
			'class'		=> $data['class'],
			'width'		=> $data['width'],
			'height'	=> $data['height'],
			
		))->whereEquals('id', $data['id'])
		  ->execute();
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
	 * Fetches all categories
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->queryAll();
	}
}
