<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Menu\Storage\ItemMapperInterface;

final class ItemMapper extends AbstractMapper implements ItemMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_menu_items';
	}

	/**
	 * Fetches category id by its associated web page id
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function fetchCategoryIdByWebPageId($webPageId)
	{
		return $this->fetchOneColumn('category_id', 'web_page_id', $webPageId);
	}

	/**
	 * Fetches all items associated with given web page id
	 * 
	 * @param string $webPageId
	 * @return array
	 */
	public function fetchAllByWebPageId($webPageId)
	{
		return $this->findAllByColumn('web_page_id', $webPageId);
	}

	/**
	 * Saves "drag and drop" move
	 * 
	 * @param string $id
	 * @param string $parentId
	 * @param integer $range
	 * @return boolean
	 */
	public function save($id, $parentId, $range)
	{
		$data = array(
			'range' => $range,
			'parent_id' => $parentId,
			'id' => $id
		);

		return $this->persist($data);
	}

	/**
	 * Fetches item's name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
	}

	/**
	 * Fetches all items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllByCategoryId($categoryId)
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('category_id', $categoryId)
						->orderBy('range')
						->queryAll();
	}

	/**
	 * Fetches all published items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId)
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->andWhereEquals('category_id', $categoryId)
						->andWhereEquals('published', '1')
						->orderBy('range')
						->queryAll();
	}

	/**
	 * Deletes an item by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Deletes all items associated with given category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteAllByCategoryId($categoryId)
	{
		return $this->deleteByColumn('category_id', $categoryId);
	}

	/**
	 * Delete all items associated with given parent id
	 * 
	 * @param string $parentId
	 * @return boolean
	 */
	public function deleteAllByParentId($parentId)
	{
		return $this->deleteByColumn('parent_id', $parentId);
	}

	/**
	 * Deletes all items by associated web page id
	 * 
	 * @param string $webPageId
	 * @return boolean
	 */
	public function deleteAllByWebPageId($webPageId)
	{
		return $this->deleteByColumn('web_page_id', $webPageId);
	}

	/**
	 * Fetches an item by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetches all items
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->findAllByColumn('lang_id', $this->getLangId());
	}

	/**
	 * Inserts an item
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates an item
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->persist($data);
	}
}
