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
	protected $table = 'bono_module_shop_orders_info';

	/**
	 * Returns shared select query
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery()
	{
		return $this->db->select('*')
						->from($this->table)
						->orderBy('id')
						->desc();
	}

	/**
	 * Adds new order data
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(

			'name' => $data['name'],
			'phone' => $data['phone'],
			'address' => $data['address'],
			'comment' => $data['comment'],
			'delivery' => $data['delivery'],
			'timestamp' => $data['timestamp'],
			'approved' => $data['approved'],
			'qty' => $data['qty'],
			'total_price' => $data['total_price']

		))->execute();
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
		return $this->db->delete()
						->from($this->table)
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Approves an order by its associated id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function approveById($id)
	{
		return $this->db->update($this->table, array('approved' => '1'))
						->whereEquals('id', $id)
						->execute();
	}
}
