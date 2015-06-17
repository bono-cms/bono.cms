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

final class CategoryImageManagerFactory extends AbstractImageManagerFactory
{
	/**
	 * {@inheritDoc}
	 */
	protected function getPath()
	{
		return '/data/uploads/module/shop/categories/';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getConfig()
	{
		return array(
			'thumb' => array(
				'dimensions' => array(
					// For the administration panel
					array(200, 200),
					// For the site
					array($this->config->getCategoryCoverWidth(), $this->config->getCategoryCoverHeight())
				)
			),

			'original' => array(
				'prefix' => 'original'
			)
		);
	}
}
