<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Controller\Admin;

use Krystal\Validate\Pattern;
use Cms\Controller\Admin\AbstractConfigController;

final class Config extends AbstractConfigController
{
	/**
	 * {@inheritDoc}
	 */
	protected function getValidationRules()
	{
		return array(
			'per_page_count' => new Pattern\PerPageCount(),
		);
	}
}
