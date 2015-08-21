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

use Krystal\Image\Tool\ImageManagerInterface;
use Krystal\Security\Filter;
use Krystal\Stdlib\ArrayUtils;
use Menu\Contract\MenuAwareManager;
use News\Storage\PostMapperInterface;
use News\Storage\CategoryMapperInterface;
use News\Service\TimeBagInterface;
use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Cms\Service\HistoryManagerInterface;

final class PostManager extends AbstractManager implements PostManagerInterface, MenuAwareManager
{
	/**
	 * Any-compliant post mapper
	 * 
	 * @var \News\Service\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * Any-compliant category mapper
	 * 
	 * @var \News\Service\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Image manager for posts
	 * 
	 * @var \Krystal\Image\Tool\ImageManager
	 */
	private $imageManager;

	/**
	 * URL manager is responsible for slugs
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * History Manager to keep track
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * Time bag to represent a timestamp in different formats
	 * 
	 * @var \News\Service\TimeBagInterface
	 */
	private $timeBag;

	/**
	 * State initialization
	 * 
	 * @param \News\Service\PostMapperInterface $postMapper Any compliant post mapper
	 * @param \News\Service\CategoryMapperInterface $categoryMapper Any compliant category mapper
	 * @param \News\Service\TimeBagInterface $timeBag Time bag to represent timestamp in different formats
	 * @param \News\Service\WebPageManagerInterface $webPageManager Web page manager to handle web pages
	 * @param \Krystal\Image\Tool\ImageManager $imageManager Image manager to handle post's cover and its paths
	 * @param \Cms\Service\HistoryManagerInterface $historyManager History manager to keep track of latest actions
	 * @return void
	 */
	public function __construct(
		PostMapperInterface $postMapper, 
		CategoryMapperInterface $categoryMapper, 
		TimeBagInterface $timeBag,
		WebPageManagerInterface $webPageManager, 
		ImageManagerInterface $imageManager,
		HistoryManagerInterface $historyManager
	){
		$this->postMapper = $postMapper;
		$this->categoryMapper = $categoryMapper;
		$this->timeBag = $timeBag;
		$this->webPageManager = $webPageManager;
		$this->imageManager = $imageManager;
		$this->historyManager = $historyManager;
	}

	/**
	 * Returns category breadcrumbs with additional appends
	 * 
	 * @param string $id Category's id
	 * @param array $appends
	 * @return array
	 */
	private function getWithCategoryBreadcrumbs($id, array $appends)
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

	/**
	 * Returns post breadcrumb collection for view
	 * 
	 * @param \News\Service\PostEntity $post
	 * @return array
	 */
	public function getBreadcrumbs(PostEntity $post)
	{
		return $this->getWithCategoryBreadcrumbs($post->getCategoryId(), array(
			array(
				'name' => $post->getTitle(),
				'link' => '#',
			)
		));
	}

	/**
	 * Fetches title by web page id
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function fetchTitleByWebPageId($webPageId)
	{
		return $this->postMapper->fetchTitleByWebPageId($webPageId);
	}

	/**
	 * Increments view count by post id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function incrementViewCount($id)
	{
		return $this->postMapper->incrementViewCount($id);
	}

	/**
	 * Update published by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair)
	{
		foreach ($pair as $id => $published) {
			if (!$this->postMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Updates SEO values by associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair)
	{
		foreach ($pair as $id => $seo) {
			if (!$this->postMapper->updateSeoById($id, $seo)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Delete posts by their associated ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->removeAllById($id)) {
				return false;
			}
		}

		$this->track('%s posts have been removed', count($ids));
		return true;
	}

	/**
	 * Deletes a post by its associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		// Grab post's title before we remove it
		$title = Filter::escape($this->postMapper->fetchTitleById($id));

		if ($this->removeAllById($id)) {
			$this->track('Post "%s" has been removed', $title);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Completely removes a post by its associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	private function removeAllById($id)
	{
		// Order of execution is important
		$this->removeWebPageById($id);
		$this->removeImagesById($id);

		return $this->postMapper->deleteById($id);
	}

	/**
	 * Removes all images by associated post id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	private function removeImagesById($id)
	{
		return $this->imageManager->delete($id);
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
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->postMapper->getPaginator();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $post)
	{
		// Configure image bag
		$imageBag = clone $this->imageManager->getImageBag();
		$imageBag->setId((int) $post['id'])
				 ->setCover($post['cover']);
		
		// Configure time bag now
		$timeBag = clone $this->timeBag;
		$timeBag->setTimestamp((int) $post['timestamp']);

		// And finally prepare post's entity
		$entity = new PostEntity();
		$entity->setImageBag($imageBag)
			->setTimeBag($timeBag)
			->setId((int) $post['id'])
			->setLangId((int) $post['lang_id'])
			->setWebPageId((int) $post['web_page_id'])
			->setCategoryId((int) $post['category_id'])
			->setPublished((bool) $post['published'])
			->setSeo((bool) $post['seo'])
			->setTitle(Filter::escape($post['title']))
			->setCategoryName(Filter::escape($this->categoryMapper->fetchTitleById($post['category_id'])))
			->setIntro(Filter::escapeContent($post['intro']))
			->setFull(Filter::escapeContent($post['full']))
			->setSlug(Filter::escape($this->webPageManager->fetchSlugByWebPageId($post['web_page_id'])))
			->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
			->setPermanentUrl('/module/news/post/'.$entity->getId())
			->setTimestamp((int) $post['timestamp'])
			->setKeywords(Filter::escape($post['keywords']))
			->setMetaDescription(Filter::escape($post['meta_description']))
			->setCover(Filter::escape($post['cover']))
			->setViewCount((int) $post['views']);

		return $entity;
	}

	/**
	 * Fetches dummy post entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy()
	{
		$timeBag = clone $this->timeBag;
		$timeBag->setTimestamp(time());

		$post = new PostEntity();
		$post->setPublished(true)
			 ->setSeo(true)
			 ->setTimeBag($timeBag);

		return $post;
	}

	/**
	 * Returns an id of latest post
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->postMapper->getLastId();
	}

	/**
	 * Prepares raw input, before sending to the mapper
	 * 
	 * @param array $input Raw input data
	 * @return array
	 */
	private function prepareInput(array $input)
	{
		$data =& $input['data']['post'];
		$data['timestamp'] = strtotime($data['date']);

		// Take a slug from a title if empty
		if (empty($data['slug'])) {
			$data['slug'] = $data['title'];
		}

		$data['slug'] = $this->webPageManager->sluggify($data['slug']);
		return $input;
	}

	/**
	 * Adds a post
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);
		$data =& $input['data']['post'];

		// By default there's 0 views
		$data['views'] = 0;

		// Handle cover
		if (!empty($input['files']['file'])) {
			$file =& $input['files']['file'];
			$this->filterFileInput($file);

			$data['cover'] = $file[0]->getName();
		} else {
			$data['cover'] = '';
		}

		$this->postMapper->insert(ArrayUtils::arrayWithout($data, array('date', 'slug')));

		// Not sure about this one
		if (!empty($input['files'])) {

			$file =& $input['files']['file'];
			$this->imageManager->upload($this->getLastId(), $file);
		}

		$this->track('New post "%s" has been created', $data['title']);
		$this->webPageManager->add($this->getLastId(), $data['slug'], 'News (Posts)', 'News:Post@indexAction', $this->postMapper);

		return true;
	}

	/**
	 * Updates a post
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function update(array $input)
	{
		$form = $this->prepareInput($input);
		$post =& $form['data']['post'];

		// Allow to remove a cover, only it case it exists and checkbox was checked
		if (isset($post['remove_cover']) && !empty($post['cover'])) {
			// Remove a cover, but not a dir itself
			$this->imageManager->delete($post['id'], $post['cover']);
			$post['cover'] = '';

		} else {

			// Do the check only in case cover doesn't need to be removed
			if (!empty($form['files'])) {
				// If we have a previous cover, then we gotta remove it
				if (!empty($post['cover'])) {
					// Remove previous one
					$this->imageManager->delete($post['id'], $post['cover']);
				}

				$file = $form['files']['file'];

				// Before we start uploading a file, we need to filter its base name
				$this->filterFileInput($file);
				$this->imageManager->upload($post['id'], $file);

				// Now override cover's value with file's base name we currently have from user's input
				$post['cover'] = $file[0]->getName();
			}
		}

		// Update a record itself now
		$this->postMapper->update(ArrayUtils::arrayWithout($post, array('date', 'slug', 'remove_cover')));

		// Update a slug
		$this->webPageManager->update($post['web_page_id'], $post['slug']);

		// And finally now just track it
		$this->track('Post "%s" has been updated', $post['title']);

		return true;
	}

	/**
	 * Fetches a post by its associated 
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->postMapper->fetchById($id));
	}

	/**
	 * Fetches all posts filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->postMapper->fetchAllByPage($page, $itemsPerPage, false));
	}

	/**
	 * Fetches all posts associated with category id and filtered by pagination
	 * 
	 * @param string $id Category id
	 * @param boolean $published Whether to fetch only published records
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($id, $published, $page, $itemsPerPage)
	{
		return $this->prepareResults($this->postMapper->fetchAllByCategoryIdAndPage($id, $published, $page, $itemsPerPage));
	}

	/**
	 * Fetches all published post bags associated with category id
	 * 
	 * @param string $categoryId
	 * @param integer $limit Amount of returned posts
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId, $limit)
	{
		return $this->prepareResults($this->postMapper->fetchAllPublishedByCategoryId($categoryId, $limit));
	}

	/**
	 * Fetches random published posts
	 * 
	 * @param integer $amount
	 * @param string $categoryId Optionally can be filtered by category id
	 * @return array
	 */
	public function fetchRandomPublished($amount, $categoryId = null)
	{
		return $this->prepareResults($this->postMapper->fetchRandomPublished($amount, $categoryId));
	}

	/**
	 * Fetches all posts
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return ($this->postMapper->fetchAll());
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
