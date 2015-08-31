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

use Cms\Storage\MySQL\AbstractMapper;
use Announcement\Storage\AnnounceMapperInterface;
use Krystal\Db\Sql\RawSqlFragment;

final class AnnounceMapper extends AbstractMapper implements AnnounceMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_announcement_announces';
	}

	/**
	 * Returns shared select
	 * 
	 * @param boolean $published
	 * @param string $categoryId
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published, $categoryId = null)
	{
		$db = $this->db->select('*')
					   ->from(static::getTableName())
					   ->whereEquals('lang_id', $this->getLangId());

		if ($categoryId !== null) {
			$db->andWhereEquals('category_id', $categoryId);
		}

		if ($published === true) {
			$db->andWhereEquals('published', '1')
			   ->orderBy(new RawSqlFragment('`order`, CASE WHEN `order` = 0 THEN `id` END DESC'));
		} else {
			$db->orderBy('id')
			   ->desc();
		}

		return $db;
	}

	/**
	 * Queries for a result
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to sort only published records
	 * @param string $categoryId Optional category id
	 * @return array
	 */
	private function getResults($page, $itemsPerPage, $published, $categoryId = null)
	{
		return $this->getSelectQuery($published, $categoryId)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Deletes all announces associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteAllByCategoryId($categoryId)
	{
		return $this->deleteByColumn('category_id', $categoryId);
	}

	/**
	 * Deletes an announce by its associated id
	 * 
	 * @param string $id Announce id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Adds an announce
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates an announce
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}

	/**
	 * Updates the sort order
	 * 
	 * @param string $id PK's value
	 * @param string $order New sort order
	 * @return boolean
	 */
	public function updateOrderById($id, $order)
	{
		return $this->updateColumnByPk($id, 'order', $order);
	}

	/**
	 * Updates SEO value
	 * 
	 * @param string $id
	 * @param string $seo Either 0 or 1
	 * @return boolean
	 */
	public function updateSeoById($id, $seo)
	{
		return $this->updateColumnByPk($id, 'seo', $seo);
	}

	/**
	 * Updates published value
	 * 
	 * @param string $id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnByPk($id, 'published', $published);
	}

	/**
	 * Fetches announce title by its associated id
	 * 
	 * @param string $id Announce id
	 * @return string
	 */
	public function fetchTitleById($id)
	{
		return $this->findColumnByPk($id, 'title');
	}

	/**
	 * Fetches announce data by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetches all published announces associated with provided category id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($id)
	{
		return $this->getSelectQuery(false, $id)
					->queryAll();
	}

	/**
	 * Fetches all announces filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, false);
	}

	/**
	 * Fetches all published announces filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, true);
	}

	/**
	 * Fetches all published announces filter by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, false, $categoryId);
	}

	/**
	 * Fetches all published announces
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->getSelectQuery(true)
					->queryAll();
	}
}
