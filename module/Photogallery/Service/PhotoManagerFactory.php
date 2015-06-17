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

final class PhotoManagerFactory extends AbstractImageManagerFactory
{
	/**
	 * {@inheritDoc}
	 */
	protected function getPath()
	{
		return '/data/uploads/module/photogallery/';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getConfig()
	{
		return array(
			'thumb' => array(
				'quality' => $this->config->getQuality(),
				'dimensions' => array(
					// Dimensions for administration panel
					array(400, 200),
					// Dimensions for site previews. 200 are default values
					array($this->config->getWidth(), $this->config->getHeight())
				)
			),
			
			'original' => array(
				'quality' => $this->config->getQuality(),
				'prefix' => 'original',
				'max_width' => $this->config->getMaxWidth(),
				'max_height' => $this->config->getMaxHeight(),
			)
		);
	}
}
