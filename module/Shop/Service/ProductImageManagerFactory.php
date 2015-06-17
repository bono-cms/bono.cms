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

use Krystal\Image\Tool\AbstractImageManagerFactory;

final class ProductImageManagerFactory extends AbstractImageManagerFactory
{
	/**
	 * {@inheritDoc}
	 */
	protected function getPath()
	{
		return '/data/uploads/module/shop/products/';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getConfig()
	{
		return array(
			'thumb' => array(
				'dimensions' => array(
					// In product's page (Administration area)
					array(200, 200),
					// Dimensions for a main cover image on site
					array($this->config->getCoverWidth(), $this->config->getCoverHeight()),
					// In category (and in browser)
					array($this->config->getCategoryCoverWidth(), $this->config->getCategoryCoverHeight()),
					// Thumbs on site
					array($this->config->getThumbWidth(), $this->config->getThumbHeight()),
				)
			),
			'original' => array(
				'prefix' => 'original'
			)
		);
	}
}
