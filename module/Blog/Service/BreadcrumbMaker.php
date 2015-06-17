<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

use Cms\Service\WebPageManagerInterface;
use Blog\Storage\CategoryMapperInterface;

final class BreadcrumbMaker implements BreadcrumbMakerInterface
{
	/**
	 * Web page manager to grab URLs
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * Any compliant category mapper
	 * 
	 * @var \Blog\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * State initialization
	 * 
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Blog\Storage\CategoryMapperInterface $categoryMapper
	 * @return void
	 */
	public function __construct(WebPageManagerInterface $webPageManager, CategoryMapperInterface $categoryMapper)
	{
		$this->webPageManager = $webPageManager;
		$this->categoryMapper = $categoryMapper;
	}

	/**
	 * Gets category breadcrumbs with appends
	 * 
	 * @param string $id Category's id
	 * @param array $appends
	 * @return array
	 */
	public function getWithCategoryBreadcrumbsById($id, array $appends)
	{
		return array_merge($this->getCategoryBreadcrumbsById($id), $appends);
	}

	/**
	 * Returns breadcrumbs for provided category id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	public function getCategoryBreadcrumbsById($id)
	{
		$category = $this->categoryMapper->fetchBcDataById($id);
		
		// Additional security check
		if (empty($category)) {
			return array(
				array()
			);
		}

		return array(
			array(
				'name' => $category['title'],
				'link' => $this->webPageManager->getUrlByWebPageId($category['web_page_id'])
			)
		);
	}
}
