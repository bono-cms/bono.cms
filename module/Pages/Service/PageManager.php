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

use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Cms\Service\HistoryManagerInterface;
use Pages\Storage\PageMapperInterface;
use Pages\Storage\DefaultMapperInterface;
use Menu\Contract\MenuAwareManager;
use Menu\Service\MenuWidgetInterface;
use Krystal\Security\Filter;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Db\Filter\FilterableServiceInterface;

final class PageManager extends AbstractManager implements PageManagerInterface, FilterableServiceInterface, MenuAwareManager
{
	/**
	 * Any compliant page mapper
	 * 
	 * @var \Pages\Storage\PageMapperInterface
	 */
	private $pageMapper;

	/**
	 * A mapper which is responsible for handling default page ids with language associations
	 * 
	 * @var \Page\Storage\DefaultMapper
	 */
	private $defaultMapper;

	/**
	 * Web page manager is responsible for managing slugs
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * History Manager to track activity
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Page\Storage\PageMapperInterface $pageMapper
	 * @param \Page\Storage\DefaultMapper $defaultMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @param \Menu\Service\MenuWidgetInterface $menuWidget Optional menu widget service
	 * @return void
	 */
	public function __construct(
		PageMapperInterface $pageMapper, 
		DefaultMapperInterface $defaultMapper, 
		WebPageManagerInterface $webPageManager, 
		HistoryManagerInterface $historyManager,
		MenuWidgetInterface $menuWidget = null
	){
		$this->pageMapper = $pageMapper;
		$this->defaultMapper = $defaultMapper;
		$this->webPageManager = $webPageManager;
		$this->historyManager = $historyManager;

		$this->setMenuWidget($menuWidget);
	}

	/**
	 * Fetches web page id by associated page id
	 * 
	 * @param string $id Page id
	 * @return string
	 */
	public function fetchWebPageIdById($id)
	{
		return $this->pageMapper->fetchWebPageIdById($id);
	}

	/**
	 * Filters the input
	 * 
	 * @param array $input Raw input data
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function filter(array $input, $page, $itemsPerPage)
	{
		return $this->prepareResults($this->pageMapper->filter($input, $page, $itemsPerPage));
	}

	/**
	 * Returns default web page id
	 * 
	 * @return integer
	 */
	public function getDefaultWebPageId()
	{
		$id = $this->defaultMapper->fetchDefaultId();
		return (int) $this->pageMapper->fetchWebPageIdByPageId($id);
	}

	/**
	 * Return breadcrumbs for a page bag
	 * 
	 * @param \Pages\Service\PageEntity $page
	 * @return array
	 */
	public function getBreadcrumbs(PageEntity $page)
	{
		return array(
			array(
				'name' => $page->getTitle(),
				'link' => '#'
			)
		);
	}

	/**
	 * Fetches a title by web page id
	 * 
	 * @param integer $webPageId
	 * @return string
	 */
	public function fetchTitleByWebPageId($webPageId)
	{
		return $this->pageMapper->fetchTitleByWebPageId($webPageId);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $page)
	{
		// Fetch meta data
		$meta = $this->webPageManager->fetchById((int) $page['web_page_id']);

		$entity = new PageEntity();
		$entity->setId((int) $page['id'])
				->setLangId((int) $page['lang_id'])
				->setWebPageId((int) $page['web_page_id'])
				->setTitle(Filter::escape($page['title']))
				->setContent(Filter::escapeContent($page['content']))
				->setSlug(Filter::escape($meta['slug']))
				->setController($meta['controller'])
				->setTemplate($page['template'])
				->setProtected((bool) $page['protected'])
				->setDefault((bool) $this->isDefault($page['id']))
				->setSeo((bool) $page['seo'])
				->setMetaDescription(Filter::escape($page['meta_description']))
				->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
				->setPermanentUrl('/module/pages/'.$entity->getId())
				->setKeywords(Filter::escape($page['keywords']));

		return $entity;
	}

	/**
	 * Fetches entity of default page
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function fetchDefault()
	{
		$id = $this->defaultMapper->fetchDefaultId();

		if ($id) {
			return $this->fetchById($id);
		} else {
			return false;
		}
	}

	/**
	 * Updates page's SEO property by its associated id
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair)
	{
		foreach ($pair as $id => $seo) {
			if (!$this->pageMapper->updateSeoById($id, $seo)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Tells whether page id default according to language id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	private function isDefault($id)
	{
		return $id == $this->defaultMapper->fetchDefaultId();
	}

	/**
	 * Return default page ids
	 * 
	 * @return array
	 */
	private function getDefaults()
	{
		// To cache method calls
		static $defaults = null;

		if (is_null($defaults)) {
			$defaults = $this->defaultMapper->fetchAll();
		}

		return $defaults;
	}

	/**
	 * Makes a page id default one
	 * 
	 * @param string $id Some exiting page id
	 * @return boolean
	 */
	public function makeDefault($id)
	{
		if ($this->defaultMapper->exists()) {
			return $this->defaultMapper->update($id);
		} else {
			return $this->defaultMapper->insert($id);
		}
	}

	/**
	 * Fetches all page entities filtered by pagination
	 * 
	 * @param string $page Current page
	 * @param string $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->pageMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Returns prepared paginator instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->pageMapper->getPaginator();
	}

	/**
	 * Returns last page id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->pageMapper->getLastId();
	}

	/**
	 * Prepares data container before sending to the mapper
	 * 
	 * @param array $input Raw input data
	 * @return array
	 */
	private function prepareInput(array $input)
	{
		if (empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		// Make it look like a a slug now
		$input['slug'] = $this->webPageManager->sluggify($input['slug']);

		// Ensure the title is secure
		$input['title'] = Filter::escape($input['title']);

		return $input;
	}

	/**
	 * Adds a page
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);
		$input['web_page_id'] = '';

		if (!$this->pageMapper->insert(ArrayUtils::arrayWithout($input, array('controller', 'makeDefault', 'slug', 'menu')))) {
			return false;
		} else {
			// It was inserted successfully
			$id = $this->getLastId();

			// If checkbox was checked
			if (isset($input['makeDefault']) && $input['makeDefault'] == '1') {
				$this->makeDefault($id);
			}

			// Add a web page now
			if ($this->webPageManager->add($id, $input['slug'], 'Pages', 'Pages:Page@indexAction', $this->pageMapper)) {
				// Do the work in case menu widget was injected
				if ($this->hasMenuWidget()) {
					$this->addMenuItem($this->webPageManager->getLastId(), $input['title'], $input);
				}
			}

			// Track it
			$this->track('A new "%s" page has been created', $input['title']);
			return true;
		}
	}

	/**
	 * Updates a page
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$input = $this->prepareInput($input);
		$this->webPageManager->update($input['web_page_id'], $input['slug'], $input['controller']);

		if ($this->hasMenuWidget() && isset($input['menu'])) {
			$this->updateMenuItem($input['web_page_id'], $input['title'], $input['menu']);
		}

		$this->track('The page "%s" has been updated', $input['title']);

		return $this->pageMapper->update(ArrayUtils::arrayWithout($input, array('controller', 'makeDefault', 'slug', 'menu')));
	}

	/**
	 * Deletes a page by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	private function delete($id)
	{
		$webPageId = $this->pageMapper->fetchWebPageIdById($id);
		
		// @TODO: Should be removed only if no parents
		// When has parents, then replace slug to #
		$this->menuWidget->deleteAllByWebPageId($webPageId);
		
		$this->webPageManager->deleteById($webPageId);
		$this->pageMapper->deleteById($id);
		
		return true;
	}

	/**
	 * Deletes a page by its associated id
	 * 
	 * @param string $id Page's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		// Gotta grab page's title, before removing it
		$title = Filter::escape($this->pageMapper->fetchTitleById($id));

		if ($this->delete($id)) {
			$this->track('The page "%s" has been removed', $title);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Delete pages by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->delete($id)) {
				return false;
			}
		}

		$this->track('%s pages have been removed', count($ids));
		return true;
	}

	/**
	 * Fetches a record by its associated id
	 * 
	 * @param string $id
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->pageMapper->fetchById($id));
	}

	/**
	 * Tracks activity
	 * 
	 * @param string $message
	 * @param string $placeholder
	 * @return boolean
	 */
	private function track($message, $placeholder = '')
	{
		return $this->historyManager->write('Pages', $message, $placeholder);
	}
}
