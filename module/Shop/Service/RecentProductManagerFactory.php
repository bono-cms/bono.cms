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

use Krystal\Text\CsvLimitedTool;
use Krystal\Http\PersistentStorageInterface;
use Krystal\Stdlib\VirtualEntity;
use Shop\Service\RecentProduct;

final class RecentProductManagerFactory
{
	/**
	 * Builds an instance
	 * 
	 * @param \Shop\Service\ProductManagerInterface $productManager
	 * @param \Krystal\Http\PersistentStorageInterface $storage
	 * @param \Krystal\Stdlib\VirtualEntity $config
	 * @return \Shop\Service\RecentProduct
	 */
	public static function build(ProductManagerInterface $productManager, PersistentStorageInterface $storage, VirtualEntity $config)
	{
		$amount = (int) $config->getMaxRecentAmount();

		$tool = new CsvLimitedTool(null, $amount + 1);

		$rp = new RecentProduct($productManager, $tool, $storage);
		$rp->load();

		return $rp;
	}
}
