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
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(

			'lang_id'			=> $this->getLangId(),
			'web_page_id'		=> $input['web_page_id'],
			'title'				=> $input['title'],
			'description'		=> $input['description'],
			'order'				=> $input['order'],
			'seo'				=> $input['seo'],
			'keywords'			=> $input['keywords'],
			'meta_description'	=> $input['meta_description'],

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
		return $this->db->update($this->table, array(

			'title'			=> $input['title'],
			'description'	=> $input['description'],
			'order'			=> $input['order'],
			'seo'			=> $input['seo'],
			'keywords'		=> $input['keywords'],
			'meta_description' => $input['meta_description'],

		))->whereEquals('id', $input['id'])
		  ->execute();
	}
}
