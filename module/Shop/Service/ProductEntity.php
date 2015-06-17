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

use Krystal\Stdlib\VirtualEntity;

final class ProductEntity extends VirtualEntity
{
	/**
	 * Checks whether this product is marked as a special offer
	 * 
	 * @return boolean
	 */
	public function isSpecialOffer()
	{
		return $this->getSpecialOffer();
	}

	/**
	 * Checks whether product has its stoke price
	 * 
	 * @return boolean
	 */
	public function hasStokePrice()
	{
		return $this->getStokePrice() > 0;
	}

	/**
	 * Tells whether product is in stoke's state
	 * 
	 * @return boolean
	 */
	public function inStoke()
	{
		return $this->hasStokePrice();
	}
}
