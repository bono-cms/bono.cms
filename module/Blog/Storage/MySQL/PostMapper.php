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
use Blog\Storage\PostMapperInterface;

final class PostMapper extends AbstractMapper implements PostMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_blog_posts';

	/**
	 * Fetches web page ids by associated category id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	public function fetchWebPageIdsByCategoryId($id)
	{
		return $this->db->select('web_page_id')
						->from($this->table)
						->whereEquals('category_id', $id)
						->queryAll('web_page_id');
	}

	/**
	 * Fetches post name by its associated id
	 * 
	 * @param string $id Post's id
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
	 * Updates row column's value by associated id
	 * 
	 * @param string $id Post id
	 * @param string $column Column name
	 * @param string $value Column's new value
	 * @return boolean
	 */
	private function updateRowById($id, $column, $value)
	{
		return $this->db->update($this->table, array($column => $value))
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Updates post's published state
	 * 
	 * @param string $id Post id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublished($id, $published)
	{
		return $this->updateRowById($id, 'published', $published);
	}

	/**
	 * Update post comment's state, if they are enabled or not
	 * 
	 * @param string $id Post id
	 * @param string $comments Either 0 or 1
	 * @return boolean
	 */
	public function updateComments($id, $comments)
	{
		return $this->updateRowById($id, 'comments', $comments);
	}

	/**
	 * Updates post seo's state, if must be indexed or not
	 * 
	 * @parma string $id Post id
	 * @param string $seo Either 0 or 1
	 * @return boolean
	 */
	public function updateSeo($id, $seo)
	{
		return $this->updateRowById($id, 'seo', $seo);
	}

	/**
	 * Adds a post
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(

			'lang_id'		=> $this->getLangId(),
			'web_page_id' 	=> $input['web_page_id'],
			'category_id' 	=> $input['category_id'],
			'title'			=> $input['title'],
			'introduction'	=> $input['introduction'],
			'full'		=> $input['full'],
			'timestamp'	=> $input['timestamp'],
			'published'	=> $input['published'],
			'comments'	=> $input['comments'],
			'seo'		=> $input['seo'],
			'keywords'	=> $input['keywords'],
			'meta_description' => $input['metaDescription']

		))->execute();
	}

	/**
	 * Updates a post
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->db->update($this->table, array(

			'title'			=>	$input['title'],
			'category_id'	=> $input['category_id'],
			'introduction'	=> $input['introduction'],
			'full'			=> $input['full'],
			'timestamp'		=> $input['timestamp'],
			'published'		=> $input['published'],
			'comments'		=> $input['comments'],
			'seo'			=> $input['seo'],
			'keywords'		=> $input['keywords'],
			'meta_description' => $input['metaDescription']

		))->whereEquals('id', $input['id'])
		  ->execute();
	}

	/**
	 * Fetches post data by its associated id
	 * 
	 * @param string $id Post id
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
	 * Deletes a post by its associated id
	 * 
	 * @param string $id Post id
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
	 * Deletes all posts associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteByCategoryId($categoryId)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->execute();
	}

	/**
	 * Fetches all published posts
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('published', '1')
						->orderBy('timestamp')
						->queryAll();
	}

	/**
	 * Fetches all published posts associated with given category id and filtered by pagination
	 * 
	 * @param string $categoryId Target category id
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByCategoryIdAndPage($categoryId, $page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->andWhereEquals('published', '1')
						->orderBy('timestamp')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches all posts associated with given category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page
	 * @param integer Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches all published posts filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('published', '1')
						->orderBy('timestamp')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches all posts filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Count all published posts associated with given category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllPublishedByCategoryId($categoryId)
	{
		return $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->andWhereEquals('published', '1')
						->query('count');
	}

	/**
	 * Counts all posts associated with given category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllByCategoryId($categoryId)
	{
		return $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->query('count');
	}
}
