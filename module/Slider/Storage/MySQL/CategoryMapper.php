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
	public static function getTableName()
	{
		return 'bono_module_slider_category';
	}

	/**
	 * Fetches category's id by its associated class name
	 * 
	 * @param string $class Category's class name
	 * @return string
	 */
	public function fetchIdByClass($class)
	{
		return $this->fetchOneColumn('id', 'class', $class);
	}

	/**
	 * Fetches category's name by its associated id
	 * 
	 * @param string $id Category id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
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

	/**
	 * Inserts a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert(static::getTableName(), array(

			'lang_id' => $this->getLangId(),
			'name'    => $input['name'],
			'class'	  => $input['class'],
			'width'   => $input['width'],
			'height'  => $input['height']

		))->execute();
	}

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->db->update(static::getTableName(), array(

			'name'		=> $input['name'],
			'class'		=> $input['class'],
			'width'		=> $input['width'],
			'height'	=> $data['height'],

		))->whereEquals('id', $input['id'])
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
		return $this->deleteByPk($id);
	}
}
