<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

use Krystal\Text\Storage\JsonStorage;
use Krystal\Text\CollectionManager;
use Krystal\Http\PersistentStorageInterface;
use Krystal\Image\Tool\ImageBagInterface;
use Shop\Service\BasketManager;
use Shop\Storage\ProductMapperInterface;
use Cms\Service\WebPageManagerInterface;

final class BasketManagerFactory
{
	/**
	 * The key's name in a storage
	 * 
	 * @const string
	 */
	const STORAGE_NS = 'sh_bsk';

	/**
	 * Returns prepared basket manager
	 * 
	 * @param \Shop\Storage\ProductMapperInterface $productMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Krystal\Image\Tool\ImageBagInterface $imageBag
	 * @param \PersistentStorageInterface $storage
	 * @return \Shop\Service\BasketManager
	 */
	public static function build(
		ProductMapperInterface $productMapper, 
		WebPageManagerInterface $webPageManager, 
		ImageBagInterface $imageBag, 
		PersistentStorageInterface $storage
	){
		$ttl = 86400;
		return new BasketManager($productMapper, $webPageManager, $imageBag, new JsonStorage($storage, self::STORAGE_NS, $ttl), new CollectionManager());
	}
}
