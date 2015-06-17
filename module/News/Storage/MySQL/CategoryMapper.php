<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use News\Storage\CategoryMapperInterface;

final class CategoryMapper extends AbstractMapper implements CategoryMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_news_categories';

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
	 * Counts all categories
	 * 
	 * @return integer
	 */
	private function countAll()
	{
		return $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->query('count');
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
	 * Inserts a category
	 * 
	 * @param array $data Category data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(
			'lang_id'			=> $this->getLangId(),
			'web_page_id'		=> $data['web_page_id'],
			'title'				=> $data['title'],
			'description'		=> $data['description'],
			'order'				=> $data['order'],
			'seo'				=> $data['seo'],
			'keywords'			=> $data['keywords'],
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
			'order'			=> $data['order'],
			'seo'			=> $data['seo'],
			'keywords'		=> $data['keywords'],
			'meta_description' => $data['meta_description'],
			
		))->whereEquals('id', $data['id'])
		  ->execute();
	}
}
