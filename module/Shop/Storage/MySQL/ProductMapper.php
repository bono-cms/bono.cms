<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Shop\Storage\ProductMapperInterface;
use Shop\Service\CategorySortProvider;

final class ProductMapper extends AbstractMapper implements ProductMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_shop_products';
	}

	/**
	 * Returns shared select
	 * 
	 * @param boolean $published
	 * @param string $categoryId Can be filtered by category id
	 * @param string $order
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published, $categoryId = null, $order = 'id', $desc = true)
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

		$db->orderBy($order);

		if ($desc == true) {
			$db->desc();
		}

		return $db;
	}

	/**
	 * Queries for a result
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to sort only published records
	 * @param string $sort Column name to sort by
	 * @param string $categoryId Optional category id
	 * @return array
	 */
	private function getResults($page, $itemsPerPage, $published, $categoryId = null, $order = 'id', $desc = true)
	{
		return $this->getSelectQuery($published, $categoryId, $order, $desc)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Finds data by the filter
	 * 
	 * @param array $input Raw input data
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function filter(array $input, $page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->whereLike('title', '%'.$input['title'].'%', true)
						->andWhereEquals('id', $input['id'], true)
						->andWhereEquals('regular_price', $input['price'], true)
						->andWhereEquals('published', $input['published'], true)
						->andWhereEquals('seo', $input['seo'], true)
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches product's data by its associated id
	 * 
	 * @param string $id Product id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetches all product ids associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchProductIdsByCategoryId($categoryId)
	{
		return $this->fetchOneColumn('id', 'category_id', $categoryId);
	}

	/**
	 * Fetches product title by its associated id
	 * 
	 * @param string $id Product id
	 * @return string
	 */
	public function fetchTitleById($id)
	{
		return $this->findColumnByPk($id, 'title');
	}

	/**
	 * Fetches latest published products
	 * 
	 * @param integer $limit
	 * @return array
	 */
	public function fetchLatestPublished($limit)
	{
		return $this->getSelectQuery(true)
					->limit($limit)
					->queryAll();
	}

	/**
	 * Fetch latest products by associated category id
	 * 
	 * @param string $categoryId
	 * @param integer $limit
	 * @return array
	 */
	public function fetchLatestByPublishedCategoryId($categoryId, $limit)
	{
		return $this->getSelectQuery(true, $categoryId)
					->limit($limit)
					->queryAll();
	}

	/**
	 * Fetches all published products associated with category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @param string $sort Sorting type (its constant)
	 * @return array
	 */
	public function fetchAllPublishedByCategoryIdAndPage($categoryId, $page, $itemsPerPage, $sort)
	{
		$desc = false;

		switch ($sort) {

			case CategorySortProvider::SORT_ORDER:
				$order = 'order';
			break;

			case CategorySortProvider::SORT_TITLE:
				$order = 'title';
			break;

			case CategorySortProvider::SORT_PRICE_DESC:
				$order = 'regular_price';
				$desc = true;
			break;

			case CategorySortProvider::SORT_PRICE_ASC:
				$order = 'regular_price';
			break;

			case CategorySortProvider::SORT_TIMESTAMP_DESC:
				$order = 'timestamp';
				$desc = true;
			break;

			case CategorySortProvider::SORT_TIMESTAMP_ASC:
				$order = 'timestamp';
			break;

			default:
				// Invalid sorting constant's value provided. Probably a user attempted to do XSS
				// We'd simply return empty result
				return array();
		}

		return $this->getResults($page, $itemsPerPage, true, $categoryId, $order, $desc);
	}

	/**
	 * Fetches all products by associated category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, false, $categoryId);
	}

	/**
	 * Fetches all product filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->getResults($page, $itemsPerPage, false);
	}

	/**
	 * Counts total amount of products associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return integer
	 */
	public function countAllByCategoryId($categoryId)
	{
		return (int) $this->db->select()
						->count('id', 'count')
						->from(static::getTableName())
						->whereEquals('category_id', $categoryId)
						->query('count');
	}

	/**
	 * Increments view count by product's id
	 * 
	 * @param string $id Product id
	 * @return boolean
	 */
	public function incrementViewCount($id)
	{
		return $this->incrementColumnByPk($id, 'views');
	}

	/**
	 * Updates a price by associated id
	 * 
	 * @param string $id Product's id
	 * @param string $price New price
	 * @return boolean
	 */
	public function updatePriceById($id, $price)
	{
		return $this->updateColumnByPk($id, 'regular_price', $price);
	}

	/**
	 * Updates published state by associated product's id
	 * 
	 * @param string $id Product's id
	 * @param string $published New state, either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnByPk($id, 'published', $published);
	}

	/**
	 * Updates SEO state by associated product's id
	 * 
	 * @param integer $id Product id
	 * @param string $published New state, either 0 or 1
	 * @return boolean
	 */
	public function updateSeoById($id, $seo)
	{
		return $this->updateColumnByPk($id, 'seo', $seo);
	}

	/**
	 * Updates a product
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}

	/**
	 * Adds a product
	 *  
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Deletes all products associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteByCategoryId($categoryId)
	{
		return $this->deleteByColumn('category_id', $categoryId);
	}

	/**	
	 * Deletes a product by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}
}
