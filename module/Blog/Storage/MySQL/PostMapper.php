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
	 * Returns shared select query
	 * 
	 * @param boolean $published Whether to sort only published records
	 * @param string $sort Column name to sort by
	 * @param string $categoryId Optional category id
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published, $sort, $categoryId = null)
	{
		$db = $this->db->select('*')
					   ->from($this->table)
					   ->whereEquals('lang_id', $this->getLangId());

		if ($published == true) {
			$db->andWhereEquals('published', '1');
		}

		if ($categoryId !== null) {
			$db->andWhereEquals('category_id', $categoryId);
		}

		$db->orderBy('timestamp')
		   ->desc();

		return $db;
	}

	/**
	 * Queries for results
	 * 
	 * @param boolean $published Whether to sort only published records
	 * @param string $sort Column name to sort by
	 * @param string $categoryId Optional category id
	 * @return array
	 */
	private function getResults($page, $itemsPerPage, $published, $sort, $categoryId = null)
	{
		return $this->getSelectQuery($published, $sort, $categoryId)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Counts amount of categories
	 * 
	 * @param string $categoryId
	 * @param boolean $published Whether to include published in calculation
	 * @return integer
	 */
	private function getCount($categoryId, $published)
	{
		$db = $this->db->select()
					   ->count('id', 'count')
					   ->from($this->table)
					   ->whereEquals('category_id', $categoryId);

		if ($published === true) {
			$db->andWhereEquals('published', '1');
		}

		return (int) $db->query('count');
	}

	/**
	 * Deletes all data by a column
	 * 
	 * @param string $column Column name
	 * @param string $value
	 * @return boolean
	 */
	private function deleteAllByColumn($column, $value)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals($column, $value)
						->execute();
	}

	/**
	 * Decides which column to use depending on published state
	 * 
	 * @param boolean $published
	 * @return string
	 */
	private function getSortingColumn($published)
	{
		return (bool) $published ? 'timestamp' : 'id';
	}

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
		return $this->deleteAllByColumn('id', $id);
	}

	/**
	 * Deletes all posts associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteByCategoryId($categoryId)
	{
		return $this->deleteAllByColumn('category_id', $categoryId);
	}

	/**
	 * Fetches all published posts
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->getSelectQuery(true, 'timestamp', null)
					->queryAll();
	}

	/**
	 * Fetches all posts associated with given category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $published, $page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, $published, $this->getSortingColumn($published), $categoryId);
	}

	/**
	 * Fetches all posts filtered by pagination
	 * 
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($published, $page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, $published, $this->getSortingColumn($published));
	}

	/**
	 * Count all published posts associated with given category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllPublishedByCategoryId($categoryId)
	{
		return $this->getCount($categoryId, true);
	}

	/**
	 * Counts all posts associated with given category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllByCategoryId($categoryId)
	{
		return $this->getCount($categoryId, false);
	}
}
