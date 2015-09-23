<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use News\Storage\PostMapperInterface;

final class PostMapper extends AbstractMapper implements PostMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_news_posts';
	}

	/**
	 * Builds shared select query
	 * 
	 * @param boolean $published
	 * @param string $categoryId Optionally can be filtered by category id
	 * @param string $sort Column name to sort by
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published, $categoryId = null, $sort = 'id')
	{
		$db = $this->db->select('*')
					   ->from(static::getTableName())
					   ->whereEquals('lang_id', $this->getLangId());

		if ($published === true) {
			$db->andWhereEquals('published', '1');
		}

		if ($categoryId !== null) {
			$db->andWhereEquals('category_id', $categoryId);
		}

		if ($sort == 'rand') {
			$db->orderBy()
			   ->rand();
			   
		} else {
			$db->orderBy($sort)
			   ->desc();
		}

		return $db;
	}

	/**
	 * Removes all web pages by associated category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function fetchAllWebPageIdsByCategoryId($categoryId)
	{
		return $this->fetchColumns('web_page_id', 'category_id', $categoryId);
	}

	/**
	 * Fetches all post ids associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllIdsWithImagesByCategoryId($categoryId)
	{
		return $this->db->select('id')
						->from(static::getTableName())
						->whereEquals('category_id', $categoryId)
						->andWhereNotEquals('cover', '')
						->queryAll('id');
	}

	/**
	 * Increments view count by post id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function incrementViewCount($id)
	{
		return $this->incrementColumnByPk($id, 'views');
	}

	/**
	 * Update post's published state by its associated id
	 * 
	 * @param string $id Post id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnByPk($id, 'published', $published);
	}

	/**
	 * Updates whether post's seo is enabled or not by its associated id
	 * 
	 * @param string $id Post id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updateSeoById($id, $seo)
	{
		return $this->updateColumnByPk($id, 'seo', $seo);
	}

	/**
	 * Adds a post
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates a post
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}

	/**
	 * Deletes a post by its associated id
	 * 
	 * @param string $id Post id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Deletes all posts associated with given category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteAllByCategoryId($categoryId)
	{
		return $this->deleteByColumn('category_id', $categoryId);
	}

	/**
	 * Fetches post data by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetches all posts
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->findAllByColumn('lang_id', $this->getLangId());
	}

	/**
	 * Fetches random published posts
	 * 
	 * @param integer $amount
	 * @param string $categoryId Optionally can be filtered by category id
	 * @return array
	 */
	public function fetchRandomPublished($amount, $categoryId = null)
	{
		return $this->getSelectQuery(true, $categoryId, 'rand')
					->limit($amount)
					->queryAll();
	}

	/**
	 * Fetches all published posts
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->getSelectQuery(true, null, 'timestamp')
					->queryAll();
	}

	/**
	 * Fetches all published posts associated with given category id
	 * 
	 * @param string $categoryId
	 * @param integer $limit Limit for posts to be fetched
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId, $limit)
	{
		return $this->getSelectQuery(true, $categoryId)
					->limit($limit)
					->queryAll();
	}

	/**
	 * Fetches all posts filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to fetch only published records
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage, $published)
	{
		return $this->getSelectQuery($published)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches all posts by associated category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $published, $page, $itemsPerPage)
	{
		return $this->getSelectQuery($published, $categoryId)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches post title by its associated id
	 * 
	 * @param string $id Post id
	 * @return string
	 */
	public function fetchTitleById($id)
	{
		return $this->findColumnByPk($id, 'title');
	}

	/**
	 * Counts all posts by associated category id
	 * Public intentionally
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllByCategoryId($categoryId)
	{
		return $this->countByColumn('category_id', $categoryId);
	}
}
