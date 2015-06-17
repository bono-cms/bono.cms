<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Service;

use Krystal\Image\Tool\AbstractImageManagerFactory;

final class TeamImageManagerFactory extends AbstractImageManagerFactory
{
	/**
	 * {@inheritDoc}
	 */
	protected function getPath()
	{
		return '/data/uploads/module/team/';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getConfig()
	{
		return array(
			'thumb' => array(
				'dimensions' => array(
					// For administration
					array(400, 200),
					// For site
					array(170, 170)
				)
			),
			
			// @TODO: Needed it even?
			'original' => array(
				'prefix' => 'original'
			)
		);
	}
}
