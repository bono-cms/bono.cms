<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Service;

use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Cms\Service\HistoryManagerInterface;
use Announcement\Storage\AnnounceMapperInterface;
use Announcement\Storage\CategoryMapperInterface;
use Menu\Contract\MenuAwareManager;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;

final class AnnounceManager extends AbstractManager implements AnnounceManagerInterface, MenuAwareManager
{
	/**
	 * Any compliant announce mapper
	 * 
	 * @var \Announcement\Storage\AnnounceMapperInterface
	 */
	private $announceMapper;

	/**
	 * Any compliant category mapper
	 * 
	 * @var \Announcement\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Web page manager for dealing with slugs
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
	 * @param \Announcement\Storage\AnnounceMapperInterface $announceMapper Any mapper which implements AnnounceMapperInterface
	 * @param \Announcement\Storage\CategoryMapperInterface $categoryMapper Any mapper which implements CategoryMapperInterface
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(
		AnnounceMapperInterface $announceMapper, 
		CategoryMapperInterface $categoryMapper, 
		WebPageManagerInterface $webPageManager,
		HistoryManagerInterface $historyManager
	){
		$this->announceMapper = $announceMapper;
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
		return $this->historyManager->write('Announcement', $message, $placeholder);
	}

	/**
	 * Returns breadcrumb collection
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $announce
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $announce)
	{
		return array(
			array(
				'name' => $announce->getTitle(),
				'link' => '#'
			)
		);
	}

	/**
	 * Fetches a title by web page id
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function fetchTitleByWebPageId($webPageId)
	{
		return $this->announceMapper->fetchTitleByWebPageId($webPageId);
	}

	/**
	 * Updates published state by associated announce ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair)
	{
		foreach ($pair as $id => $published) {
			if (!$this->announceMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Updates SEO state by associated announce ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair)
	{
		foreach ($pair as $id => $seo) {
			if (!$this->announceMapper->updateSeoById($id, $seo)) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Returns last announce id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->announceMapper->getLastId();
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->announceMapper->getPaginator();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $announce)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $announce['id'])
			->setCategoryId((int) $announce['category_id'])
			->setLangId((int) $announce['lang_id'])
			->setWebPageId((int) $announce['web_page_id'])
			->setCategoryName(Filter::escape($this->categoryMapper->fetchNameById($announce['category_id'])))
			->setTitle(Filter::escape($announce['title']))
			->setName(Filter::escape($announce['name']))
			->setIntro(Filter::escapeContent($announce['intro']))
			->setFull(Filter::escapeContent($announce['full']))
			->setOrder((int) $announce['order'])
			->setPublished((bool) $announce['published'])
			->setSeo((bool) $announce['seo'])
			->setSlug($this->webPageManager->fetchSlugByWebPageId($announce['web_page_id']))
			->setKeywords(Filter::escape($announce['keywords']))
			->setMetaDescription(Filter::escape($announce['meta_description']))
			->setCover($announce['cover'])
			->setPermanentUrl('/module/announcement/'.$entity->getId())
			->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()));

		return $entity;
	}

	/**
	 * Prepares a container
	 * 
	 * @param array $input Raw input data
	 * @return array
	 */
	private function prepareInput(array $input)
	{
		// Empty slug is always taken from a title
		if (empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}
		
		if (!empty($container->file)) {
			//$container->cover = $container->file[0]->getName();
		} else {
			//$container->cover = '';
		}
		
		//@todo this
		$input['cover'] = '';
		
		$input['slug'] = $this->webPageManager->sluggify($input['slug']);
		return $input;
	}

	/**
	 * Adds an announce
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);
		$input['web_page_id'] = '';

		$this->announceMapper->insert(ArrayUtils::arrayWithout($input, array('slug')));

		$id = $this->getLastId();

		$this->track('Announce "%s" has been added', $input['title']);
		$this->webPageManager->add($id, $input['slug'], 'Announcements', 'Announcement:Announce@indexAction', $this->announceMapper);

		return true;
	}

	/**
	 * Updates an announce
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$input = $this->prepareInput($input);
		$this->webPageManager->update($input['web_page_id'], $input['slug']);

		$this->track('Announce "%s" has been updated', $input['title']);

		return $this->announceMapper->update(ArrayUtils::arrayWithout($input, array('slug')));
	}

	/**
	 * Fetches announce's entity by its associated id
	 * 
	 * @param string $id
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->announceMapper->fetchById($id));
	}

	/**
	 * Fetches all published announce entities filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->announceMapper->fetchAllPublishedByPage($page, $itemsPerPage));
	}

	/**
	 * Fetches all announce entities filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->announceMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Fetches all announce entities associated with provided category id and filtered by pagination
	 * 
	 * @param string $categoryId
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByCategoryIdAndPage($categoryId, $page, $itemsPerPage)
	{
		return $this->prepareResults($this->announceMapper->fetchAllByCategoryIdAndPage($categoryId, $page, $itemsPerPage));
	}

	/**
	 * Fetches all published announce entities associated with provided category id
	 * 
	 * @param string $categoryId
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($categoryId)
	{
		return $this->prepareResults($this->announceMapper->fetchAllPublishedByCategoryId($categoryId));
	}

	/**
	 * Deletes an announce by its associated id
	 * 
	 * @param string $id Announce id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		// Grab announce's title before we remove it
		$title = Filter::escape($this->announceMapper->fetchTitleById($id));

		if ($this->delete($id)) {
			$this->track('Announce "%s" has been removed', $title);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Delete announces by their associated ids
	 * 
	 * @param array $ids Array of announce ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->delete($id)) {
				return false;
			}
		}
		
		$this->track('Batch removal of %s announces', count($ids));
		return true;
	}

	/**
	 * Deletes an announce by its associated id
	 * 
	 * @param string $id Announce id
	 * @return boolean
	 */ 
	private function delete($id)
	{
		$webPageId = $this->announceMapper->fetchWebPageIdById($id);
		$this->webPageManager->deleteById($webPageId);
		
		return $this->announceMapper->deleteById($id);
	}
}
