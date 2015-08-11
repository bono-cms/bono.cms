<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

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

		$entity->setShowCaseCount((int) $this->get('showcase_count'))
			   ->setSpecialSupport((bool) $this->get('special_support'))
			   ->setStokePriceEnabled((bool) $this->get('stoke_price_enabled'))
			   ->setDefaultCategoryPerPageCount((int) $this->get('default_category_per_page_count'))
			   ->setCurrency(Filter::escape($this->get('currency')))
			   ->setMaxRecentAmount((int) $this->get('recent_max_amount', 3))
			   ->setBasketPageId(Filter::escape($this->get('basket_page_id', 0)))
			   ->setStokePerPageCount($this->get('stoke_per_page_count', 10));

		$entity->setBasketStorageType($this->get('basket_storage_type', 'cookies'));
		$entity->setBasketStorageTypes(array(
			'session' => 'Save data until a user closes a browser (In session)',
			'cookies' => 'Save data forever (In cookies)'
		));
		
		$entity->setCoverHeight((float) $this->get('cover_height'))
			   ->setCoverWidth((float) $this->get('cover_width'))
			   ->setThumbHeight((float) $this->get('thumb_height'))
			   ->setThumbWidth((float) $this->get('thumb_width'))
			   ->setCategoryCoverHeight((float) $this->get('category_cover_height'))
			   ->setCategoryCoverWidth((float) $this->get('category_cover_width'));
	}
}
