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

use News\Storage\CategoryMapperInterface;
use Cms\Service\WebPageManagerInterface;

final class BreadcrumbMaker implements BreadcrumbMakerInterface
{
	/**
	 * Any complaint category mapper
	 * 
	 * @var \News\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Web page manager to build URLs
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * State initialization
	 * 
	 * @param \News\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function __construct(CategoryMapperInterface $categoryMapper, WebPageManagerInterface $webPageManager)
	{
		$this->categoryMapper = $categoryMapper;
		$this->webPageManager = $webPageManager;
	}

	/**
	 * Returns category breadcrumbs with additional appends
	 * 
	 * @param string $id Category's id
	 * @param array $appends
	 * @return array
	 */
	public function getWithCategoryBreadcrumbs($id, array $appends)
	{
		return array_merge($this->getCategoryBreadcrumbsById($id), $appends);
	}

	/**
	 * Returns category breadcrumbs by its associated id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	private function getCategoryBreadcrumbsById($id)
	{
		$category = $this->categoryMapper->fetchById($id);
		$categoryWebPage = $this->webPageManager->fetchById($category['web_page_id']);

		return array(
			array(
				'name' => $category['title'],
				'link' => $this->webPageManager->surround($categoryWebPage['slug'], $categoryWebPage['lang_id']),
			)
		);
	}
}
