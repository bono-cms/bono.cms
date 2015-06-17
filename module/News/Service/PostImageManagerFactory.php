<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

use Krystal\Image\Tool\AbstractImageManagerFactory;

final class PostImageManagerFactory extends AbstractImageManagerFactory
{
	/**
	 * {@inheritDoc}
	 */
	protected function getPath()
	{
		return '/data/uploads/module/news/posts/';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getConfig()
	{
		return array(
			'thumb' => array(
				'quality' => $this->config->getCoverQuality(),
				'dimensions' => array(
					// For administration panel
					array(200, 200),
					
					// Dimensions for the site
					array($this->config->getCoverWidth(), $this->config->getCoverHeight()),
					array($this->config->getThumbWidth(), $this->config->getThumbHeight()),
				)
			)
		);
	}
}
