<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Service;

use Krystal\Image\Tool\AbstractImageManagerFactory;

final class AlbumImageManagerFactory extends AbstractImageManagerFactory
{
	/**
	 * {@inheritDoc}
	 */
	protected function getPath()
	{
		return '/data/uploads/module/photogallery/albums';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getConfig()
	{
		return array(
			'thumb' => array(
				'dimensions' => array(
					// Dimensions for administration panel
					array(400, 200),
					// Dimensions for the site
					array($this->config->getAlbumThumbWidth(), $this->config->getAlbumThumbHeight())
				)
			),
			'original' => array(
				'prefix' => 'original'
			)
		);
	}
}