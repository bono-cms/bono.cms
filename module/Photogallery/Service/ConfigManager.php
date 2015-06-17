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
		$entity->setPerPageCount((int) $this->get('photos_per_page', 5))
			   ->setTemplate(Filter::escape($this->get('template')))
			   ->setWidth((float) $this->get('thumb_width', 200))
			   ->setHeight((float) $this->get('thumb_height', 200))
			   ->setMaxHeight((float) $this->get('max_img_height', 200))
			   ->setMaxWidth((float) $this->get('max_img_width', 200))
			   ->setQuality((float) $this->get('quality', 75))
			   ->setLanguageSupport((bool) $this->get('language_support'))
			   ->setLanguageSupportOptions(array(
					'0' => 'One photogallery version for all languages',
					'1' => 'Each language must have its own photogallery version',
				));
	}
}
