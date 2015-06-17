<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Service;

use Cms\Service\AbstractManager;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;

abstract class AbstractItemService extends AbstractManager
{
	/**
	 * {@inheritDoc}
	 */ 
	protected function toEntity(array $item)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $item['id'])
			->setParentId((int) $item['parent_id'])
			->setCategoryId((int) $item['category_id'])
			->setWebPageId($item['web_page_id'])
			->setName(Filter::escape($item['name']))
			->setLink(Filter::escape($item['link']))
			->setHasLink((bool) $item['has_link'])
			->setHint(Filter::escape($item['hint']))
			->setOpenInNewWindow((bool) $item['open_in_new_window'])
			->setPublished((bool) $item['published']);
			
		return $entity;
	}

	/**
	 * Fetches dummy menu item's entity
	 * 
	 * @param string $categoryId
	 * @param string $parentId
	 * @return \Krystal\Stdlib\VirtualEnity
	 */
	public function fetchDummy($categoryId = null, $parentId = null)
	{
		$item = array(
			'category_id' => $categoryId,
			'web_page_id' => null,
			'parent_id' => $parentId,
			'name' => null,
			'link' => null,
			'has_link' => false,
			'id'   => null,
			'hint' => null,
			'published' => '1',
			'open_in_new_window' => '0'
		);
		
		return $this->toEntity($item);
	}
}
