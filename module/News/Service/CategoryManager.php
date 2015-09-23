<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Cms\Service\HistoryManagerInterface;
use News\Storage\CategoryMapperInterface;
use News\Storage\PostMapperInterface;
use Menu\Contract\MenuAwareManager;
use Menu\Service\MenuWidgetInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;
use Krystal\Image\Tool\ImageManagerInterface;
use Krystal\Stdlib\ArrayUtils;

final class CategoryManager extends AbstractManager implements CategoryManagerInterface, MenuAwareManager
{
	/**
	 * Any compliant category mapper
	 * 
	 * @var \News\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Any compliant post mapper
	 * 
	 * @var \News\Storage\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * Web page manager to deal with slugs
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * History manager to track activity
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * Image manager to remove post images when removing a category
	 * 
	 * @var \Krystal\Image\Tool\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * State initialization
	 * 
	 * @param \News\Storage\CategoryMapperInterface $categoryMapper
	 * @param \News\Storage\PostMapperInterface $postMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @param \Krystal\Image\Tool\ImageManager $imageManager
	 * @param \Menu\Service\MenuWidgetInterface $menuWidget Optional menu widget
	 * @return void
	 */
	public function __construct(
		CategoryMapperInterface $categoryMapper, 
		PostMapperInterface $postMapper, 
		WebPageManagerInterface $webPageManager,
		HistoryManagerInterface $historyManager,
		ImageManagerInterface $imageManager,
		MenuWidgetInterface $menuWidget = null
	){
		$this->categoryMapper = $categoryMapper;
		$this->postMapper = $postMapper;
		$this->webPageManager = $webPageManager;
		$this->historyManager = $historyManager;
		$this->imageManager = $imageManager;

		$this->setMenuWidget($menuWidget);
	}

	/**
	 * Returns breadcrumbs for view
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $category
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $category)
	{
		return array(
			array(
				'name' => $category->getTitle(),
				'link' => '#',
			)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function fetchTitleByWebPageId($webPageId)
	{
		return $this->categoryMapper->fetchTitleByWebPageId($webPageId);
	}

	/**
	 * Returns category's last id
	 * 
	 * @return string
	 */
	public function getLastId()
	{
		return $this->categoryMapper->getLastId();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $category)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $category['id'])
			->setWebPageId((int) $category['web_page_id'])
			->setLangId((int) $category['lang_id'])
			->setTitle(Filter::escape($category['title']))
			->setDescription(Filter::escapeContent($category['description']))
			->setSlug(Filter::escape($this->webPageManager->fetchSlugByWebPageId($category['web_page_id'])))
			->setSeo((bool) $category['seo'])
			->setKeywords(Filter::escape($category['keywords']))
			->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
			->setMetaDescription(Filter::escape($category['meta_description']));
			
		return $entity;
	}

	/**
	 * Fetches categories as a list
	 * 
	 * @return array
	 */
	public function fetchList()
	{
		return ArrayUtils::arrayList($this->categoryMapper->fetchList(), 'id', 'title');
	}

	/**
	 * Fetches all category entities
	 * 
	 * @return array|boolean
	 */
	public function fetchAll()
	{
		return $this->prepareResults($this->categoryMapper->fetchAll());
	}

	/**
	 * Prepares raw form data before sending to the mapper
	 * 
	 * @param array $input Raw input data
	 * @return array
	 */
	private function prepareInput(array $input)
	{
		$category =& $input['category'];

		// By default a slug is taken from a title as well
		if (empty($category['slug'])) {
			$category['slug'] = $category['title'];
		}

		// Force a string to be slugiffied
		$category['slug'] = $this->webPageManager->sluggify($category['slug']);
		return $input;
	}

	/**
	 * Adds a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);
		$category =& $input['category'];

		$category['web_page_id'] = '';

		if ($this->categoryMapper->insert(ArrayUtils::arrayWithout($category, array('slug', 'menu')))) {
			$this->track('Category "%s" has been created', $category['title']);

			if ($this->webPageManager->add($this->getLastId(), $category['slug'], 'News (Categories)', 'News:Category@indexAction', $this->categoryMapper)) {
				if ($this->hasMenuWidget()) {
					// If at least one menu widget it added
					if (isset($input['menu']['widget']) && is_array($input['menu']['widget'])) {
						$this->addMenuItem($this->webPageManager->getLastId(), $category['title'], $input);
					}
				}
			}

			return true;

		} else {
			return false;
		}
	}

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function update(array $input)
	{
		$input = $this->prepareInput($input);
		$category =& $input['category'];

		$this->webPageManager->update($category['web_page_id'], $category['slug']);

		// If at least one menu widget it added
		if ($this->hasMenuWidget() && isset($input['menu'])) {
			$this->updateMenuItem($category['web_page_id'], $category['title'], $input['menu']);
		}

		// Track it
		$this->track('Category "%s" has been updated', $category['title']);
		return $this->categoryMapper->update(ArrayUtils::arrayWithout($category, array('slug', 'menu')));
	}

	/**
	 * Fetches category bag by its associated id
	 * 
	 * @param string $id Category id
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->categoryMapper->fetchById($id));
	}

	/**
	 * Deletes a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean Depending on success
	 */
	public function deleteById($id)
	{
		// Grab category's title before we remove it
		$title = Filter::escape($this->categoryMapper->fetchTitleById($id));

		if ($this->removeAllById($id)) {
			$this->track('Category "%s" has been removed', $title);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Removes a category by it associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removeAllById($id)
	{
		// The order of execution is important
		$this->removeCategoryById($id);
		$this->removeAllPostImagesByCategoryId($id);
		$this->removeAllPostsById($id);

		return true;
	}

	/**
	 * Removes a category by its associated id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removeCategoryById($id)
	{
		$webPageId = $this->categoryMapper->fetchWebPageIdById($id);

		$this->webPageManager->deleteById($webPageId);
		$this->categoryMapper->deleteById($id);

		return true;
	}

	/**
	 * Removes all post images associated with category id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removeAllPostImagesByCategoryId($id)
	{
		$ids = $this->postMapper->fetchAllIdsWithImagesByCategoryId($id);

		// Do the work, in case there's at least one id
		if (!empty($ids)) {
			foreach ($ids as $id) {
				$this->imageManager->delete($id);
			}
		}

		return true;
	}

	/**
	 * Removes all posts associated with category id
	 * 
	 * @param string $id Category's id
	 * @return boolean
	 */
	private function removeAllPostsById($id)
	{
		$webPageIds = $this->postMapper->fetchAllWebPageIdsByCategoryId($id);

		// Remove associated web pages, first
		foreach ($webPageIds as $webPageId) {
			$this->webPageManager->deleteById($webPageId);
		}

		// And then remove all posts
		$this->postMapper->deleteAllByCategoryId($id);

		return true;
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
		return $this->historyManager->write('News', $message, $placeholder);
	}
}
