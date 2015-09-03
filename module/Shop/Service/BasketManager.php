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

use Krystal\Text\CollectionManagerInterface;
use Krystal\Text\Storage\JsonStorage;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;
use Krystal\Image\Tool\ImageBag;
use Cms\Service\WebPageManagerInterface;
use Shop\Storage\ProductMapperInterface;

final class BasketManager implements BasketManagerInterface
{
	/**
	 * Collection manager to manage product ids (increment quantity, read, delete and so on)
	 * 
	 * @var \Krystal\Text\CollectionManagerInterface
	 */
	private $collectionManager;

	/**
	 * Product mapper to grab information by stored ids in basket
	 * 
	 * @var \Shop\Storage\ProductMapperInterface
	 */
	private $productMapper;

	/**
	 * Order mapper is used for order tracks
	 * 
	 * @var \Shop\Storage\OrderMapper
	 */
	private $orderMapper;

	/**
	 * Product's image bag
	 * 
	 * @var \Krystal\Image\Tool\ImageBagInterface
	 */
	private $imageBag;

	/**
	 * Web page manager for grabbing slugs
	 * 
	 * @var \Cms\Service\WebPageManager
	 */
	private $webPageManager;

	const BASKET_STATIC_OPTION_QTY = 'c';
	const BASKET_STATIC_OPTION_SUBTOTAL_PRICE = 'p';

	/**
	 * State initialization
	 * 
	 * @param \Shop\Storage\ProductMapperInterface $productMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Krystal\Image\ImageBag $imageBag
	 * @param \Krystal\Text\Storage\JsonStorage $storage
	 * @param \Krystal\Text\CollectionManagerInterface $collection
	 * @return void
	 */
	public function __construct(
		ProductMapperInterface $productMapper,
		WebPageManagerInterface $webPageManager,
		ImageBag $imageBag,
		JsonStorage $storage,
		CollectionManagerInterface $collection
	){
		$this->productMapper = $productMapper;
		$this->webPageManager = $webPageManager;
		$this->imageBag = $imageBag;
		$this->storage = $storage;
		$this->collection = $collection;
	}

	/**
	 * Saves changes to a storage
	 * 
	 * @return \Shop\Service\BasketManager
	 */
	public function save()
	{
		$this->storage->save($this->collection->getContainer());
		return $this;
	}

	/**
	 * Loads data from a storage
	 * 
	 * @return void
	 */
	public function load()
	{
		$data = $this->storage->load();
		$this->collection->load($data);
	}

	/**
	 * Clears the basket
	 * 
	 * @return void
	 */
	public function clear()
	{
		$this->collection->reset();
	}

	/**
	 * Returns all product entities stored in the basket
	 * 
	 * @return array
	 */
	public function getProducts()
	{
		$products = $this->collection->getContainer();
		$entities = array();

		foreach ($products as $id => $options) {
			$product = $this->productMapper->fetchById($id);

			if (empty($product)) {
				// This should never happen, but if happens, then its hacking attack
				// Or a product itself has been removed. We'd simply ignore it for now
				continue;
			} else {

				$qty = (int) $options[self::BASKET_STATIC_OPTION_QTY];
				$price =& $options[self::BASKET_STATIC_OPTION_SUBTOTAL_PRICE];

				$imageBag = clone $this->imageBag;
				$imageBag->setId((int) $product['id'])
						 ->setCover(Filter::escape($product['cover']));

				// Grab actual price
				$price = $this->getPrice($product);

				// Now finally prepare the entity
				$entity = new VirtualEntity();
				$entity->setId((int) $product['id'])
						   ->setTitle(Filter::escape($product['title']))
						   ->setUrl($this->webPageManager->getUrl($product['web_page_id'], $product['lang_id']))
						   ->setImageBag($imageBag)
						   ->setQty($qty)
						   ->setPrice($price)
						   ->setSubTotalPrice($qty * $price);

				// Finally add prepared entity
				array_push($entities, $entity);
			}
		}

		// And return in reversed order
		return array_reverse($entities, true);
	}

	/**
	 * Returns static by associated product id stored in the basket
	 * 
	 * @param string $id Product id
	 * @return array
	 */
	public function getProductStat($id)
	{
		return array(
			'totalQuantity' => $this->collection->getWithOption($id, self::BASKET_STATIC_OPTION_QTY),
			'totalPrice' => $this->collection->getWithOption($id, self::BASKET_STATIC_OPTION_SUBTOTAL_PRICE)
		);
	}

	/**
	 * Returns total products quantity and total price of them all
	 * 
	 * @return array
	 */
	public function getAllStat()
	{
		// Cache method calls
		static $stat = null;

		if (is_null($stat)) {
			$stat = array(
				'totalQuantity' => array_sum($this->collection->getAllOptions(self::BASKET_STATIC_OPTION_QTY)),
				'totalPrice' => array_sum($this->collection->getAllOptions(self::BASKET_STATIC_OPTION_SUBTOTAL_PRICE))
			);
		}

		return $stat;
	}

	/**
	 * Returns total price of all products stored in the basket
	 * 
	 * @return float
	 */
	public function getTotalPrice()
	{
		$stat = $this->getAllStat();
		return $stat['totalPrice'];
	}

	/**
	 * Returns total quantity
	 * 
	 * @return integer
	 */
	public function getTotalQuantity()
	{
		$stat = $this->getAllStat();
		return $stat['totalQuantity'];
	}

	/**
	 * Recounts price with new quantity
	 * 
	 * @param string $id Product id
	 * @param integer $newQty New quantity
	 * @return boolean
	 */
	public function recount($id, $newQty)
	{
		// Ensure the expected type is present
		$newQty = (int) $newQty;

		// Get current values from collection, first
		$qty = $this->collection->getWithOption($id, self::BASKET_STATIC_OPTION_QTY);
		$subTotalPrice = $this->collection->getWithOption($id, self::BASKET_STATIC_OPTION_SUBTOTAL_PRICE);

		// Ensure that target values aren't damaged, that's a basic security check
		if ($subTotalPrice !== false && $qty !== false) {
			// Get the price of a single unit
			$price = $subTotalPrice / $qty;

			$this->collection->updateWithOption($id, self::BASKET_STATIC_OPTION_QTY, $newQty)
							 ->updateWithOption($id, self::BASKET_STATIC_OPTION_SUBTOTAL_PRICE, $price * $newQty);

			return true;

		} else {
			return false;
		}
	}

	/**
	 * Adds product's id to the basket
	 * 
	 * @param string $id Product id
	 * @param integer $qty Quantity of product ids to be added
	 * @return boolean
	 */
	public function add($id, $qty)
	{
		// Ensure product id exists, firstly. This is for synchronization
		$product = $this->productMapper->fetchById($id);

		// If id exists, then it will never be empty
		if (!empty($product)) {
			$price = $this->getPrice($product);

			// If we adding the same id twice, then we need to update its data instead
			if ($this->collection->hasKey($id)) {
				return $this->update($id, $qty, $price);
			} else {
				return $this->insert($id, $qty, $price);
			}

		} else {
			// Wrong id supplied, therefore can't add it
			return false;
		}
	}

	/**
	 * Removes a product from a basket by its associated id
	 * 
	 * @param string $id Product id to be removed
	 * @param boolean
	 */
	public function removeById($id)
	{
		$this->collection->removeKey($id);
		return true;
	}

	/**
	 * Inserts a product into a basket
	 * 
	 * @param string $id Product id
	 * @param integer $qty Quantity of products
	 * @param float $price Product's price
	 * @return boolean
	 */
	private function insert($id, $qty, $price)
	{
		$qty = (int) $qty;

		// We're adding a product for the first time
		$this->collection->addWithOption($id, self::BASKET_STATIC_OPTION_QTY, $qty)
						 ->addWithOption($id, self::BASKET_STATIC_OPTION_SUBTOTAL_PRICE, $qty * $price);

		return true;
	}

	/**
	 * Updates a product in a basket
	 * 
	 * @param string $id Product id
	 * @param integer $qty New quantity
	 * @param float $price New Price
	 * @return boolean Depending on success
	 */
	private function update($id, $qty, $price)
	{
		$qty = (int) $qty;

		// Get current quantity
		$previousQty = $this->collection->getWithOption($id, self::BASKET_STATIC_OPTION_QTY);

		// That's just a security check
		if ($previousQty === false) {
			return false;
		} else {
			$previousQty = intval($previousQty);
		}

		// Now the same security check for the previous price
		$previousPrice = $this->collection->getWithOption($id, self::BASKET_STATIC_OPTION_SUBTOTAL_PRICE);

		if ($previousPrice === false) {
			return false;
		} else {
			$previousPrice = (float) $previousPrice;
		}

		$this->collection->updateWithOption($id, self::BASKET_STATIC_OPTION_QTY, $previousQty + $qty)
						 ->updateWithOption($id, self::BASKET_STATIC_OPTION_SUBTOTAL_PRICE, $previousPrice + ($price * $qty));

		// If we reached here, then it needs to be considered as success
		return true;
	}

	/**
	 * Returns actual price
	 * 
	 * @param array $product
	 * @return integer|float
	 */
	private function getPrice(array $product)
	{
		if (isset($product['stoke_price']) && $product['stoke_price'] > 0) {
			return $product['stoke_price'];
		} else {
			return $product['regular_price'];
		}
	}
}
