<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Controller\Admin;

use Cms\Controller\Admin\AbstractConfigController;
use Krystal\Validate\Pattern;

final class Config extends AbstractConfigController
{
	/**
	 * {@inheritDoc}
	 */
	protected function getValidationRules()
	{
		return array(
			'max_img_height' => new Pattern\ImageHeight(),
			'max_img_width' => new Pattern\ImageWidth(),
			'photos_per_page' => new Pattern\PerPageCount(),
			'quality' => new Pattern\ImageQuality(),
			'thumb_height' => new Pattern\ThumbHeight(),
			'thumb_width' => new Pattern\ThumbWidth()
		);
	}
}
