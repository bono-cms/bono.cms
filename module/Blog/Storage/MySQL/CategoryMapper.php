<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
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
	protected $table = 'bono_module_blog_categories';

	/**
	 * @param string $id
	 * @return array
	 */
	public function fetchBcDataById($id)
	{
		return $this->db->select(array('title', 'web_page_id'))
						->from($this->table)
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
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->queryAll();
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
			
			'lang_id'		=> $this->getLangId(),
			'web_page_id'	=> $data['web_page_id'],
			'title'			=> $data['title'],
			'description'	=> $data['description'],
			'seo'			=> $data['seo'],
			'order'			=> $data['order'],
			'keywords'		=> $data['keywords'],
			'meta_description'	=> $data['meta_description'],
			
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
			
			'title'			=> $data['title'],
			'description'	=> $data['description'],
			'seo'			=> $data['seo'],
			'order'			=> $data['order'],
			'keywords'		=> $data['keywords'],
			'meta_description' => $data['meta_description'],
			
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
	 * Fetches category name by its associated id
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
