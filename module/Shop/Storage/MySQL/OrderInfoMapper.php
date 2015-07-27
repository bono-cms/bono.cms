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
use Shop\Storage\OrderInfoMapperInterface;

final class OrderInfoMapper extends AbstractMapper implements OrderInfoMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_shop_orders_info';
	}

	/**
	 * Filters the input
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
						->whereLike('name', '%'.$input['name'].'%', true)
						->andWhereLike('phone', '%'.$input['phone'].'%', true)
						->andWhereEquals('id', $input['id'], true)
						->andWhereEquals('date', $input['date'], true)
						->andWhereEquals('qty', $input['qty'], true)
						->andWhereEquals('total_price', $input['total_price'], true)
						->andWhereEquals('approved', $input['approved'], true)
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Returns shared select query
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery()
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->orderBy('id')
						->desc();
	}

	/**
	 * Counts all orders
	 * 
	 * @param boolean $approved Whether to count only approved orders
	 * @return integer
	 */
	public function countAll($approved)
	{
		$db = $this->db->select()
					   ->count('id', 'count')
					   ->from(self::getTableName());

		if ($approved === true) {
			$db->whereEquals('approved', '1');
		}

		return $db->query('count');
	}

	/**
	 * Adds new order data
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->persist($data);
	}

	/**
	 * Fetches latest orders
	 * 
	 * @param integer $limit
	 * @return array
	 */
	public function fetchLatest($limit)
	{
		return $this->getSelectQuery()
					->limit($limit)
					->queryAll();
	}

	/**
	 * Fetches all orders filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->getSelectQuery()
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Deletes an order by its associated id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Approves an order by its associated id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function approveById($id)
	{
		return $this->updateColumnByPk($id, 'approved', '1');
	}
}
