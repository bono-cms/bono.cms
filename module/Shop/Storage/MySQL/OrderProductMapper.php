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
use Shop\Storage\OrderMapperInterface;
use Shop\Storage\OrderProductMapperInterface;

final class OrderProductMapper extends AbstractMapper implements OrderProductMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_shop_orders_products';
	}

	/**
	 * Counts the sum of sold products
	 * 
	 * @return float
	 */
	public function getPriceSumCount()
	{
		return (float) $this->db->select()
							    ->sum('price', 'count')
								->from(self::getTableName())
								->query('count');
	}

	/**
	 * Counts total amount of sold products
	 * 
	 * @return integer
	 */
	public function getQtySumCount()
	{
		return (int) $this->db->select()
							  ->sum('qty', 'count')
							  ->from(self::getTableName())
							  ->query('count');
	}

	/**
	 * Deletes all products associated with provided order's id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function deleteAllByOrderId($id)
	{
		return $this->db->delete()
						->from(self::getTableName())
						->whereEquals('order_id', $id)
						->execute();
	}

	/**
	 * Add an order
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert(self::getTableName(), array(
			
			'order_id' => $data['order_id'],
			'product_id' => $data['product_id'],
			'name' => $data['name'],
			'price' => $data['price'],
			'sub_total_price' => $data['sub_total_price'],
			'qty' => $data['qty']
			
		))->execute();
	}

	/**
	 * Fetches all details by associated order's id
	 * 
	 * @param string $id Order's id
	 * @return array
	 */
	public function fetchAllDetailsByOrderId($id)
	{
		return $this->db->select('*')
						->from(self::getTableName())
						->whereEquals('order_id', $id)
						->queryAll();
	}
}
