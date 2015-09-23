<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Controller\Admin;

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
			'time_format_in_list' => new Pattern\DateFormat(),
			'time_format_in_post' => new Pattern\DateFormat(),
			'per_page_count' => new Pattern\PerPageCount(),
			'block_per_page_count' => new Pattern\PerPageCount(),
			'cover_quality' => new Pattern\ImageQuality(),
			'cover_height' => new Pattern\ImageHeight(),
			'cover_width' => new Pattern\ImageWidth(),
			'thumb_height' => new Pattern\ImageHeight(),
			'thumb_width' => new Pattern\ImageWidth()
		);
	}
}
