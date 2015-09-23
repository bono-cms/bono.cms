<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller\Admin;

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
			// Grab these rules from framework's patterns
			'default_category_per_page_count' => new Pattern\PerPageCount(),
			'currency' => new Pattern\Currency(),
			'showcase_count' => new Pattern\PerPageCount(),
			'category_cover_height' => new Pattern\ImageHeight(),
			'category_cover_width' => new Pattern\ImageWidth(),
			'cover_width' => new Pattern\ImageWidth(),
			'cover_height' => new Pattern\ImageHeight(),
			'thumb_height' => new Pattern\ImageHeight(),
			'thumb_width' => new Pattern\ImageWidth(),
		);
	}
}
