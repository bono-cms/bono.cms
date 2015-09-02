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
use Krystal\Image\Tool\ImageManager;
use Krystal\Config\File\FileArray;
use Cms\AbstractCmsModule;
use Shop\Service\ProductImageManagerFactory;
use Shop\Service\CategoryImageManagerFactory;
use Shop\Service\RecentProductManagerFactory;
use Shop\Service\BasketManagerFactory;
use Shop\Service\BasketManager;
use Shop\Service\ProductManagerInterface;

abstract class AbstractShopModule extends AbstractCmsModule
{
	/**
	 * Returns product image manager
	 * 
	 * @return \Krystal\Image\ImageManager
	 */
	final protected function getProductImageManager()
	{
		$config = $this->getConfigEntity();

		$plugins = array(
			'thumb' => array(
				'dimensions' => array(
					// In product's page (Administration area)
					array(200, 200),
					// Dimensions for a main cover image on site
					array($config->getCoverWidth(), $config->getCoverHeight()),
					// In category (and in browser)
					array($config->getCategoryCoverWidth(), $config->getCategoryCoverHeight()),
					// Thumbs on site
					array($config->getThumbWidth(), $config->getThumbHeight()),
				)
			),
			'original' => array(
				'prefix' => 'original'
			)
		);

		return new ImageManager(
			'/data/uploads/module/shop/products/',
			$this->appConfig->getRootDir(),
			$this->appConfig->getRootUrl(),
			$plugins
		);
	}

	/**
	 * Returns category image manager
	 * 
	 * @return \Krystal\Image\ImageManager
	 */
	final protected function getCategoryImageManager()
	{
		$config = $this->getConfigEntity();

		$plugins = array(
			'thumb' => array(
				'dimensions' => array(
					// For the administration panel
					array(200, 200),
					// For the site
					array($config->getCategoryCoverWidth(), $config->getCategoryCoverHeight())
				)
			),
			'original' => array(
				'prefix' => 'original'
			)
		);

		return new ImageManager(
			'/data/uploads/module/shop/categories/',
			$this->appConfig->getRootDir(),
			$this->appConfig->getRootUrl(),
			$plugins
		);
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
	final protected function getBasketManager($productMapper, ImageBagInterface $imageBag)
	{
		return BasketManagerFactory::build($productMapper, $this->getWebPageManager(), $imageBag, $this->getStorage());
	}
}
