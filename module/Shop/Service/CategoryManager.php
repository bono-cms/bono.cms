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

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Cms\Service\WebPageManagerInterface;
use Menu\Contract\MenuAwareManager;
use Menu\Service\MenuWidgetInterface;
use Shop\Storage\CategoryMapperInterface;
use Shop\Storage\ProductMapperInterface;
use Krystal\Image\Tool\ImageManagerInterface;
use Krystal\Security\Filter;
use Krystal\Tree\AdjacencyList\TreeBuilder;
use Krystal\Tree\AdjacencyList\Render\PhpArray;
use Krystal\Stdlib\ArrayUtils;

final class CategoryManager extends AbstractManager implements CategoryManagerInterface, MenuAwareManager
{
	/**
	 * Any compliant category mapper
	 * 
	 * @var \Shop\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Any compliant product mapper
	 * 
	 * @var \Shop\Storage\ProductMapperInterface
	 */
	private $productMapper;

	/**
	 * Web page manager to manage slugs
	 * 
	 * @var \Cms\Service\WebPageManager
	 */
	private $webPageManager;

	/**
	 * Image manager for categories. It can upload, remove and build paths for images
	 * 
	 * @var \Krystal\Image\Tool\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * History manager to keep tracks
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * Internal service to remove category's products
	 * 
	 * @var \Shop\Service\ProductRemoverInterface
	 */
	private $productRemover;

	/**
	 * State initialization
	 * 
	 * @param \Shop\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Shop\Storage\ProductMapperInterface $productMapper
	 * @param \Cms\Service\WebPageManager $webPageManager
	 * @param \Krystal\Image\ImageManagerInterface $imageManager
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @param \Menu\Service\MenuWidgetInterface $menuWidget
	 * @return void
	 */
	public function __construct(
		CategoryMapperInterface $categoryMapper, 
		ProductMapperInterface $productMapper, 
		WebPageManagerInterface $webPageManager, 
		ImageManagerInterface $imageManager,
		HistoryManagerInterface $historyManager,
		ProductRemoverInterface $productRemover,
		MenuWidgetInterface $menuWidget = null
	){
		$this->categoryMapper = $categoryMapper;
		$this->productMapper = $productMapper;
		$this->webPageManager = $webPageManager;
		$this->imageManager = $imageManager;
		$this->historyManager = $historyManager;
		$this->productRemover = $productRemover;

		$this->setMenuWidget($menuWidget);
	}
	
	/**
	 * Fetches all categories as a tree
	 * 
	 * @return array
	 */
	public function fetchAllAsTree()
	{
		$treeBuilder = new TreeBuilder($this->fetchAll());
		return $treeBuilder->render(new PhpArray('title'));
	}

	/**
	 * {@inheritDoc}
	 */
	public function fetchTitleByWebPageId($webPageId)
	{
		return $this->categoryMapper->fetchTitleByWebPageId($webPageId);
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
		return $this->historyManager->write('Shop', $message, $placeholder);
	}

	/**
	 * Returns category's breadcrumbs
	 * 
	 * @param \Shop\Service\CategoryEntity $category
	 * @return array
	 */
	public function getBreadcrumbs(CategoryEntity $category)
	{
		$bm = new BreadcrumbMaker($this->categoryMapper, $this->webPageManager);
		return $bm->getBreadcrumbsById($category->getId());
	}

	/**
	 * Fetches all categories
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->categoryMapper->fetchAll();
	}

	/**
	 * Counts all available categories
	 * 
	 * @return integer
	 */
	public function countAll()
	{
		return $this->categoryMapper->countAll();
	}

	/**
	 * Returns last category's id
	 * 
	 * @return integer
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
		$imageBag = clone $this->imageManager->getImageBag();
		$imageBag->setId((int) $category['id'])
				 ->setCover($category['cover']);

		$entity = new CategoryEntity();
		$entity->setId((int) $category['id'])
			->setImageBag($imageBag)
			->setParentId((int) $category['parent_id'])
			->setLangId((int) $category['lang_id'])
			->setWebPageId($category['web_page_id'])
			->setTitle(Filter::escape($category['title']))
			->setDescription(Filter::escapeContent($category['description']))
			->setOrder((int) $category['order'])
			->setSeo((bool) $category['seo'])
			->setSlug(Filter::escape($this->webPageManager->fetchSlugByWebPageId($category['web_page_id'])))
			->setKeywords(Filter::escape($category['keywords']))
			->setPermanentUrl('/module/shop/category/'.$entity->getId())
			->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
			->setMetaDescription(Filter::escape($category['meta_description']))
			->setCover(Filter::escape($category['cover']));

		return $entity;
	}

	/**
	 * Prepares raw input data before sending it to the mapper
	 * 
	 * @param array $input Raw form data
	 * @return array
	 */
	private function prepareInput(array $input)
	{
		$category =& $input['data']['category'];

		if (empty($category['slug'])) {
			// Empty slug by default is taken from a title
			$category['slug'] = $category['title'];
		}

		$category['slug'] = $this->webPageManager->sluggify($category['slug']);
		return $input;
	}

	/**
	 * Updates a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		// First, we need to parse raw form data
		$input = $this->prepareInput($input);

		$category =& $input['data']['category'];

		// Allow to remove a cover, only it case it exists and checkbox was checked
		if (isset($category['remove_cover']) && !empty($category['cover'])) {

			// Remove a cover, but not a dir itself
			$this->imageManager->delete($category['id']);
			$category['cover'] = '';

		} else {

			if (!empty($input['files']['file'])) {
				$file =& $input['files']['file'];

				// If we have a previous cover's image, then we need to remove it
				if (!empty($category['cover'])) {
					if (!$this->imageManager->delete($category['id'], $category['cover'])){
						// If failed, then exit this method immediately
						return false;
					}
				}

				// And now upload a new one
				$this->filterFileInput($file);
				$category['cover'] = $file[0]->getName();

				$this->imageManager->upload($category['id'], $file);
			}
		}

		$this->webPageManager->update($category['web_page_id'], $category['slug']);
		$this->categoryMapper->update(ArrayUtils::arrayWithout($category, array('slug', 'menu', 'remove_cover')));

		if ($this->hasMenuWidget() && isset($input['data']['menu'])) {
			$this->updateMenuItem($category['web_page_id'], $category['title'], $input['data']['menu']);
		}

		$this->track('Category "%s" has been updated', $category['title']);
		return true;
	}

	/**
	 * Adds a category
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);
		$category =& $input['data']['category'];

		// Cover is always empty by default
		$category['cover'] = '';

		// If we have a cover, then we need to upload it
		if (!empty($input['files']['file'])) {
			$file =& $input['files']['file'];

			// Now filter original file's name
			$this->filterFileInput($file);

			// Override empty cover's value now
			$category['cover'] = $file[0]->getName();
		}

		$category['web_page_id'] = '';

		if ($this->categoryMapper->insert(ArrayUtils::arrayWithout($category, array('slug', 'menu')))) {
			$id = $this->getLastId();

			// If we have a cover, then we need to upload it
			if (!empty($input['files']['file'])) {
				$this->imageManager->upload($id, $input['files']['file']);
			}

			$this->track('Added category "%s"', $category['title']);

			if ($this->webPageManager->add($id, $category['slug'], 'Shop (Categories)', 'Shop:Category@indexAction', $this->categoryMapper)) {
				// Do the work in case menu widget was injected
				if ($this->hasMenuWidget()) {
					$this->addMenuItem($this->webPageManager->getLastId(), $category['title'], $input['data']);
				}
			}

			return true;
		}
	}

	/**
	 * Removes a category by its associated id
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	public function removeById($id)
	{
		$this->removeCategoryById($id);
		$this->removeChildNodes($id);

		return true;
	}

	/**
	 * Removes a category by its associated id (Including images if present)
	 * 
	 * @param string $id
	 * @return boolean
	 */
	private function removeCategoryById($id)
	{
		$this->removeWebPageById($id);

		$this->categoryMapper->deleteById($id);
		$this->imageManager->delete($id);

		// Remove associated products
		$this->productRemover->removeAllProductsByCategoryId($id);

		return true;
	}
	
	/**
	 * Removes all child nodes
	 * 
	 * @param string $parentId Parent category's id
	 * @return boolean
	 */
	private function removeChildNodes($parentId)
	{
		$treeBuilder = new TreeBuilder($this->categoryMapper->fetchAll());
		$ids = $treeBuilder->findChildNodeIds($parentId);

		// If there's at least one child id, then start working next
		if (!empty($ids)) {
			foreach ($ids as $id) {
				$this->removeCategoryById($id);
			}
		}

		return true;
	}

	/**
	 * Removes category's web page
	 * 
	 * @param string $id Category id
	 * @return boolean
	 */
	private function removeWebPageById($id)
	{
		$webPageId = $this->categoryMapper->fetchWebPageIdById($id);
		return $this->webPageManager->deleteById($webPageId);
	}

	/**
	 * Fetches category's entity by its associated id
	 * 
	 * @param string $id Category id
	 * @return \Shop\Service\CategoryEntity|boolean
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->categoryMapper->fetchById($id));
	}
}
