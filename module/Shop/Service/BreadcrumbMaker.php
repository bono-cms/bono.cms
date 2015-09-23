<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Service;

use Shop\Storage\CategoryMapperInterface;
use Cms\Service\WebPageManagerInterface;
use Krystal\Tree\AdjacencyList\BreadcrumbBuilder;

final class BreadcrumbMaker implements BreadcrumbMakerInterface
{
	/**
	 * Any compliant category mapper
	 * 
	 * @var \Shop\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Web page manager to generate URLs
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * State initialization
	 * 
	 * @param \Shop\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function __construct(CategoryMapperInterface $categoryMapper, WebPageManagerInterface $webPageManager)
	{
		$this->categoryMapper = $categoryMapper;
		$this->webPageManager = $webPageManager;
	}

	/**
	 * Gets breadcrumbs with appends
	 * 
	 * @param string $id Category's id
	 * @param array $appends Additional appends
	 * @return array
	 */
	public function getWithCategoryId($id, array $appends)
	{
		return array_merge($this->getBreadcrumbsById($id), $appends);
	}

	/**
	 * Gets all breadcrumbs by associated id
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	public function getBreadcrumbsById($id)
	{
		$wm = $this->webPageManager;
		$builder = new BreadcrumbBuilder($this->categoryMapper->fetchBcData(), $id);

		return $builder->makeAll(function($breadcrumb) use ($wm) {
			return array(
				'name' => $breadcrumb['title'],
				'link' => $wm->getUrl($breadcrumb['web_page_id'], $breadcrumb['lang_id'])
			);
		});
	}
}
