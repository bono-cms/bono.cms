<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop;

use Krystal\Image\Tool\ImageBagInterface;
use Krystal\Config\File\FileArray;
use Cms\AbstractCmsModule;
use Shop\Service\ProductImageManagerFactory;
use Shop\Service\CategoryImageManagerFactory;
use Shop\Service\RecentProductManagerFactory;
use Shop\Service\BasketManagerFactory;
use Shop\Service\BasketManager;
use Shop\Service\ConfigManager;
use Shop\Service\ProductManagerInterface;

abstract class AbstractShopModule extends AbstractCmsModule
{
	/**
	 * Returns configuration entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	final protected function getConfigEntity()
	{
		return $this->getConfigManager()->getEntity();
	}

	/**
	 * Returns product image manager
	 * 
	 * @return \Krystal\Image\ImageManager
	 */
	final protected function getProductImageManager()
	{
		$factory = new ProductImageManagerFactory($this->getAppConfig(), $this->getConfigEntity());
		return $factory->build();
	}

	/**
	 * Returns category image manager
	 * 
	 * @return \Krystal\Image\ImageManager
	 */
	final protected function getCategoryImageManager()
	{
		$factory = new CategoryImageManagerFactory($this->getAppConfig(), $this->getConfigEntity());
		return $factory->build();
	}

	/**
	 * Returns manager for recent products
	 * 
	 * @param \Shop\Service\ProductManagerInterface $productManager
	 * @return \Shop\Service\RecentProduct
	 */
	final protected function getRecentProduct(ProductManagerInterface $productManager)
	{
		return RecentProductManagerFactory::build($productManager, $this->getStorage(), $this->getConfigEntity());
	}

	/**
	 * Returns storage manager
	 * 
	 * @return \Krystal\Http\PersistentStorageInterface
	 */
	final protected function getStorage()
	{
		if ($this->getConfigEntity()->getBasketStorageType() == 'cookies') {
			return $this->getServiceLocator()->get('request')->getCookieBag();
		} else {
			// Always session storage by default
			return $this->getServiceLocator()->get('sessionBag');
		}
	}

	/**
	 * Returns an instance of basket manager
	 * 
	 * @param \Shop\Storage\ProductMapperInterface $productMapper
	 * @param \Krystal\Image\Tool\ImageBagInterface $imageBag
	 * @return \Shop\Service\BasketManager
	 */
	protected function getBasketManager($productMapper, ImageBagInterface $imageBag)
	{
		return BasketManagerFactory::build($productMapper, $this->getWebPageManager(), $imageBag, $this->getStorage());
	}

	/**
	 * Returns configuration manager
	 * 
	 * @return \Shop\Service\ConfigManager
	 */
	protected function getConfigManager()
	{
		static $config = null;

		if (is_null($config)) {
			$adapter = new FileArray(__DIR__.'/Config/module.config.php');
			$adapter->load();
			
			$config = new ConfigManager($adapter);
		}
		
		return $config;
	}
}
