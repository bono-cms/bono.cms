<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Service;

use Krystal\Config\AbstractConfigManager;
use Krystal\Security\Filter;

final class ConfigManager extends AbstractConfigManager
{
	/**
	 * {@inheritDoc}
	 */
	protected function populate()
	{
		$entity = $this->getEntity();
		$entity->setHomeTemplate(Filter::escape($this->get('home_template', 'pages-home')))
			   ->setPageTemplate(Filter::escape($this->get('page_template', 'pages-page')))
			   ->setNfTemplate(Filter::escape($this->get('nf_template'), 'pages-404'));
	}
}
