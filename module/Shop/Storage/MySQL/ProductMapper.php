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
	protected $table = 'bono_module_shop_products';

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
						->from($this->table)
						->whereLike('title', '%'.$input['title'].'%', true)
						->andWhereEquals('id', $input['id'], true)
						->andWhereEquals('regular_price', $input['price'], true)
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches all product ids associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchProductIdsByCategoryId($categoryId)
	{
		return $this->db->select('id')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->queryAll('id');
	}

	/**
	 * Fetches product title by its associated id
	 * 
	 * @param string $id Product id
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
	 * Fetches latest published products
	 * 
	 * @param integer $limit
	 * @return array
	 */
	public function fetchLatestPublished($limit)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->andWhereEquals('category_id', $categoryId)
						->orderBy('id')
						->desc()
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

		// Build firstly, static part of query which always unchanged
		$db = $this->db->select('*')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->andWhereEquals('published', '1');

		// Sort order is usually dynamic
		$db->orderBy($order);

		// We need to append direction right after limit
		if ($desc == true) {
			$db->desc();
		}

		return $db->paginate($page, $itemsPerPage)
				  ->queryAll();
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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
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
						->from($this->table)
						->whereEquals('category_id', $categoryId)
						->query('count');
	}

	/**
	 * Updates a row by id
	 * 
	 * @param string $id Product's id
	 * @param string $column Target column
	 * @param string $value New value
	 * @return boolean
	 */
	private function updateRowById($id, $column, $value)
	{
		return $this->db->update($this->table, array($column => $value))
						->whereEquals('id', $id)
						->execute();
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
		return $this->updateRowById($id, 'regular_price', $price);
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
		return $this->updateRowById($id, 'published', $published);
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
		return $this->updateRowById($id, 'seo', $seo);
	}

	/**
	 * Updates a product
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->db->update($this->table, array(

			'category_id'		=> $input['category_id'],
			'title'				=> $input['title'],
			'regular_price'		=> $input['regular_price'],
			'stoke_price'		=> $input['stoke_price'],
			'description'		=> $input['description'],
			'published'			=> $input['published'],
			'order'				=> $input['order'],
			'seo'				=> $input['seo'],
			'keywords'			=> $input['keywords'],
			'meta_description'  => $input['meta_description'],
			'cover'				=> $input['cover']

		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Adds a product
	 *  
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(

			'lang_id'			=> $this->getLangId(),
			'category_id'		=> $input['category_id'],
			'title'				=> $input['title'],
			'regular_price'		=> $input['regular_price'],
			'stoke_price'		=> $input['stoke_price'],
			'special_offer'		=> $input['special_offer'],
			'description'		=> $input['description'],
			'published'			=> $input['published'],
			'order'				=> $input['order'],
			'seo'				=> $input['seo'],
			'keywords'			=> $input['keywords'],
			'meta_description'	=> $input['meta_description'],
			'cover'				=> $input['cover'],
			'timestamp'			=> $input['timestamp']

		))->execute();
	}

	/**
	 * Fetches product's data by its associated id
	 * 
	 * @param string $id Product id
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
	 * Deletes all products associated with provided category id
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
	 * Deletes a product by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('id', $id)
						->execute();
	}
}
