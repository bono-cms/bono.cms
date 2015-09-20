<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

use Cms\Service\AbstractManager;
use Cms\Service\MailerInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Db\Filter\FilterableServiceInterface;
use Shop\Storage\OrderInfoMapperInterface;
use Shop\Storage\OrderProductMapperInterface;

final class OrderManager extends AbstractManager implements OrderManagerInterface, FilterableServiceInterface
{
	/**
	 * Any compliant order information mapper
	 * 
	 * @var \Shop\Storage\OrderMapperInterface
	 */
	private $orderInfoMapper;

	/**
	 * Any compliant order's product mapper
	 * 
	 * @var \Shop\Storage\OrderProductMapperInteface
	 */
	private $orderProductMapper;

	/**
	 * Basket manager
	 * 
	 * @var \Shop\Service\BasketManagerInterface
	 */
	private $basketManager;

	/**
	 * State initialization
	 * 
	 * @param \Shop\Storage\OrderInfoMapperInterface $orderMapper
	 * @param \Shop\Storage\OrderProductMapperInterface $orderProductMapper
	 * @param \Shop\Service\BasketManagerInterface $basketManager
	 * @return void
	 */
	public function __construct(
		OrderInfoMapperInterface $orderInfoMapper, 
		OrderProductMapperInterface $orderProductMapper, 
		BasketManagerInterface $basketManager
	){
		$this->orderInfoMapper = $orderInfoMapper;
		$this->orderProductMapper = $orderProductMapper;
		$this->basketManager = $basketManager;
	}

	/**
	 * Filters the raw input
	 * 
	 * @param array|\ArrayAccess $input Raw input data
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Items per page to be displayed
	 * @param string $sortingColumn Column name to be sorted
	 * @param string $desc Whether to sort in DESC order
	 * @return array
	 */
	public function filter($input, $page, $itemsPerPage, $sortingColumn, $desc)
	{
		return $this->prepareResults($this->orderInfoMapper->filter($input, $page, $itemsPerPage, $sortingColumn, $desc));
	}

	/**
	 * Counts the sum of sold products
	 * 
	 * @return float
	 */
	public function getPriceSumCount()
	{
		return $this->orderProductMapper->getPriceSumCount();
	}

	/**
	 * Counts total amount of sold products
	 * 
	 * @return integer
	 */
	public function getQtySumCount()
	{
		return $this->orderProductMapper->getQtySumCount();
	}

	/**
	 * Counts all orders
	 * 
	 * @param boolean $approved Whether to count only approved orders
	 * @return integer
	 */
	public function countAll($approved)
	{
		return $this->orderInfoMapper->countAll($approved);
	}

	/**
	 * Approves an order by its associated id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function approveById($id)
	{
		return $this->orderInfoMapper->approveById($id);
	}

	/**
	 * Removes an order by its associated id
	 * 
	 * @param string $id Order's id
	 * @return boolean
	 */
	public function removeById($id)
	{
		$this->orderInfoMapper->deleteById($id);
		$this->orderProductMapper->deleteAllByOrderId($id);

		return true;
	}

	/**
	 * Fetches all order's details by its associated id
	 * 
	 * @param string $id Order id
	 * @return array
	 */
	public function fetchAllDetailsByOrderId($id)
	{
		return $this->orderProductMapper->fetchAllDetailsByOrderId($id);
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->orderInfoMapper->getPaginator();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $order)
	{
		$entity = new VirtualEntity();
		$entity->setId($order['id'])
				 ->setDate($order['date'])
				 ->setName($order['name'])
				 ->setPhone($order['phone'])
				 ->setAddress($order['address'])
				 ->setComment($order['comment'])
				 ->setDelivery($order['delivery'])
				 ->setQty($order['qty'])
				 ->setTotalPrice($order['total_price'])
				 ->setApproved((bool) $order['approved']);
		
		return $entity;
	}

	/**
	 * Makes an order
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function make(array $input)
	{
		$defaults = array(
			// By default all orders are un-approved
			'approved' => '0',
			'qty' => $this->basketManager->getTotalQuantity(),
			'total_price' => $this->basketManager->getTotalPrice()
		);

		$data = array_merge($input, $defaults);
		$data['date'] = date('Y-m-d', time());

		// First of all, insert, because we need to obtain a last id
		$this->orderInfoMapper->insert(ArrayUtils::arrayWithout($data, array('captcha')));

		// Now obtain last id
		$id = $this->orderInfoMapper->getLastId();

		$products = $this->basketManager->getProducts();

		if ($this->addProducts($id, $products)) {

			// Order is saved. Now clear the basket
			$this->basketManager->clear();
			$this->basketManager->save();

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Tracks products
	 * 
	 * @param string $id Order id
	 * @param array $products
	 * @return boolean
	 */
	private function addProducts($id, array $products)
	{
		foreach ($products as $product) {
			$data = array(
				'order_id' => $id,
				'product_id' => $product->getId(),
				'name' => $product->getTitle(),
				'price' => $product->getPrice(),
				'sub_total_price' => $product->getSubTotalPrice(),
				'qty' => $product->getQty()
			);
			
			$this->orderProductMapper->insert($data);
		}

		return true;
	}

	/**
	 * Fetches latest order entities
	 * 
	 * @param integer $limit
	 * @return array
	 */
	public function fetchLatest($limit)
	{
		return $this->prepareResults($this->orderInfoMapper->fetchLatest($limit));
	}

	/**
	 * Fetches all entities filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->orderInfoMapper->fetchAllByPage($page, $itemsPerPage));
	}
}
