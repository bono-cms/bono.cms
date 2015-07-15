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
use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Blog\Storage\PostMapperInterface;
use Blog\Storage\CategoryMapperInterface;
use Menu\Contract\MenuAwareManager;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;
use Krystal\Stdlib\ArrayUtils;

final class PostManager extends AbstractManager implements PostManagerInterface, MenuAwareManager
{
	/**
	 * Any compliant post mapper
	 * 
	 * @var \Blog\Storage\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * Any compliant category mapper
	 * 
	 * @var \Blog\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Web page manager
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * History manager to keep track
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Blog\Storage\PostMapperInterface $postMapper
	 * @param \Blog\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(
		PostMapperInterface $postMapper, 
		CategoryMapperInterface $categoryMapper,
		WebPageManagerInterface $webPageManager,
		HistoryManagerInterface $historyManager
	){
		$this->postMapper = $postMapper;
		$this->categoryMapper = $categoryMapper;
		$this->webPageManager = $webPageManager;
		$this->historyManager = $historyManager;
	}

	/**
	 * Tracks activity
	 * 
	 * @param string $message
	 * @param string $placeholder
	 * @return boolean
	 */
	private function track($message, $placeholder)
	{
		return $this->historyManager->write('Blog', $message, $placeholder);
	}

	/**
	 * Gets category breadcrumbs with appends
	 * 
	 * @param string $id Category's id
	 * @param array $appends
	 * @return array
	 */
	private function getWithCategoryBreadcrumbsById($id, array $appends)
	{
		return array_merge($this->getCategoryBreadcrumbsById($id), $appends);
	}

	/**
	 * Returns breadcrumbs for provided category id
	 * 
	 * @param string $id Category's id
	 * @return array
	 */
	private function getCategoryBreadcrumbsById($id)
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

	/**
	 * Returns breadcrumb collection
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $post
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $post)
	{
		return $this->getWithCategoryBreadcrumbsById($post->getCategoryId(), array(
			array(
				'name' => $post->getTitle(),
				'link' => '#'
			)
		));
	}

	/**
	 * Fetches post title by its associated web page id
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function fetchTitleByWebPageId($webPageId)
	{
		return $this->postMapper->fetchTitleByWebPageId($webPageId);
	}

	/**
	 * Updates SEO states by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair)
	{
		foreach ($pair as $id => $seo) {
			if (!$this->postMapper->updateSeo($id, $seo)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Update comments. Enabled or disable for particular post
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateComments(array $pair)
	{
		foreach ($pair as $id => $comments) {
			if (!$this->postMapper->updateComments($id, $comments)) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Updates published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair)
	{
		foreach ($pair as $id => $published) {
			if (!$this->postMapper->updatePublished($id, $published)) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Returns time format
	 * 
	 * @return string
	 */
	public function getTimeFormat()
	{
		return 'm/d/Y';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $post)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $post['id'])
			->setLangId((int) $post['lang_id'])
			->setWebPageId((int) $post['web_page_id'])
			->setCategoryTitle(Filter::escape($this->categoryMapper->fetchTitleById($post['category_id'])))
			->setTitle(Filter::escape($post['title']))
			->setCategoryId((int) $post['category_id'])
			->setIntroduction(Filter::escapeContent($post['introduction']))
			->setFull(Filter::escapeContent($post['full']))
			->setTimestamp((int) $post['timestamp'])
			->setPublished((bool) $post['published'])
			->setComments((bool) $post['comments'])
			->setSeo((bool) $post['seo'])
			->setSlug(Filter::escape($this->webPageManager->fetchSlugByWebPageId($post['web_page_id'])))
			->setKeywords(Filter::escape($post['keywords']))
			->setMetaDescription(Filter::escape($post['meta_description']))
			->setDate(date($this->getTimeFormat(), $entity->getTimestamp()))
			->setPermanentUrl('/module/blog/post/'.$entity->getId())
			->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()));

		return $entity;
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->postMapper->getPaginator();
	}

	/**
	 * Returns last post id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->postMapper->getLastId();
	}

	/**
	 * Fetches randomly published post entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchRandomPublished()
	{
		return $this->prepareResult($this->postMapper->fetchRandomPublished());
	}

	/**
	 * Fetches all posts filtered by pagination
	 * 
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($published, $page, $itemsPerPage)
	{
		return $this->prepareResults($this->postMapper->fetchAllByPage($published, $page, $itemsPerPage));
	}

	/**
	 * Fetches all posts associated with given category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $published, $page, $itemsPerPage)
	{
		return $this->prepareResults($this->postMapper->fetchAllByCategoryIdAndPage($categoryId, $published, $page, $itemsPerPage));
	}

	/**
	 * Fetches all published post bags
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->prepareResults($this->postMapper->fetchAllPublished());
	}

	/**
	 * Fetches all published post bags filtered by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->postMapper->fetchAllPublishedByPage($page, $itemsPerPage));
	}

	/**
	 * Prepares raw input data before sending to the mapper
	 * 
	 * @param array $input
	 * @return array
	 */
	private function prepareInput(array $input)
	{
		// Empty slug is always taken from the title
		if (empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		$input['slug'] = $this->webPageManager->sluggify($input['slug']);
		$input['timestamp'] = strtotime($input['date']);

		return $input;
	}

	/**
	 * Adds a post
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);
		$input['web_page_id'] = '';

		if ($this->postMapper->insert(ArrayUtils::arrayWithout($input, array('date', 'slug')))) {
			$id = $this->getLastId();

			$this->track('Post "%s" has been added', $input['title']);
			$this->webPageManager->add($id, $input['slug'], 'Blog (Posts)', 'Blog:Post@indexAction', $this->postMapper);
		}

		return true;
	}

	/**
	 * Updates a post
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$input = $this->prepareInput($input);
		$this->webPageManager->update($input['web_page_id'], $input['slug']);

		$this->track('Post "%s" has been updated', $input['title']);
		return $this->postMapper->update(ArrayUtils::arrayWithout($input, array('date', 'slug')));
	}

	/**
	 * Fetches post entity by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->postMapper->fetchById($id));
	}

	/**
	 * Removes a web page by post's associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	private function removeWebPageById($id)
	{
		$webPageId = $this->postMapper->fetchWebPageIdById($id);
		return $this->webPageManager->deleteById($webPageId);
	}

	/**
	 * Removes all by post's associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	private function removeAllById($id)
	{
		$this->removeWebPageById($id);
		$this->postMapper->deleteById($id);

		return true;
	}

	/**
	 * Removes a post by its associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	public function removeById($id)
	{
		$title = Filter::escape($this->postMapper->fetchTitleById($id));

		if ($this->removeAllById($id)) {
			$this->track('Post "%s" has been removed', $title);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Removes posts by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function removeByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->removeAllById($id)) {
				return false;
			}
		}

		$this->track('Batch removal of %s posts', count($ids));
		return true;
	}
}
