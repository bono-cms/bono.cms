<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Blog\Storage\CategoryMapperInterface;
use Blog\Storage\PostMapperInterface;
use Krystal\Stdlib\VirtualEntity;

final class SiteService extends AbstractManager implements SiteServiceInterface
{
	/**
	 * Any-compliant category mapper
	 * 
	 * @var \Blog\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Any-complaint post mapper
	 * 
	 * @var \Blog\Storage\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * Web page manager
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * State initialization
	 * 
	 * @param \Blog\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Blog\Storage\PostMapperInterface $postMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function __construct(CategoryMapperInterface $categoryMapper, PostMapperInterface $postMapper, WebPageManagerInterface $webPageManager)
	{
		$this->categoryMapper = $categoryMapper;
		$this->postMapper = $postMapper;
		$this->webPageManager = $webPageManager;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $category)
	{
		$entity = new VirtualEntity();
		$entity->setId($category['id'])
			   ->setLangId($category['lang_id'])
			   ->setCount($this->postMapper->countAllPublishedByCategoryId($entity->getId()))
			   ->setSlug($this->webPageManager->fetchSlugByWebPageId($category['web_page_id']))
			   ->setTitle($category['title'] . sprintf(' (%s) ', $entity->getCount()))
			   ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()));
		
		return $entity;
	}

	/**
	 * Returns an array of categories with count of posts
	 * 
	 * @return array
	 */
	public function getAllCategoriesWithCount()
	{
		return $this->prepareResults($this->categoryMapper->fetchAllBasic());
	}
}
