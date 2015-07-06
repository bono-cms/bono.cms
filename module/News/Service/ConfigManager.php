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
		$entity->setCoverHeight(floatval($this->get('cover_Height', 300)))
			   ->setCoverWidth(floatval($this->get('cover_Height', 300)))
			   ->setThumbHeight(floatval($this->get('thumb_height', 30)))
			   ->setThumbWidth(floatval($this->get('thumb_width', 30)))
			   ->setCoverQuality((int) $this->get('cover_quality', 75))
			   ->setTimeFormatInList(Filter::escape($this->get('time_format_in_list', 'm/d/Y')))
			   ->setTimeFormatInPost(Filter::escape($this->get('time_format_in_post', 'm/d/Y')))
			   ->setBlockPerPageCount((int)($this->get('block_per_page_count', 3)))
			   ->setPerPageCount((int)($this->get('per_page_count', 5)));
	}
}
