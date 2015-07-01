<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Service;

use Photogallery\Storage\AlbumMapperInterface;
use Photogallery\Storage\PhotoMapperInterface;
use Cms\Service\HistoryManagerInterface;
use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Menu\Contract\MenuAwareManager;
use Menu\Service\MenuWidgetInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Image\Tool\ImageManagerInterface;
use Krystal\Security\Filter;

final class AlbumManager extends AbstractManager implements AlbumManagerInterface, MenuAwareManager
{
	/**
	 * Any-compliant album mapper
	 * 
	 * @var \Photogallery\Storage\AlbumMapperInterface
	 */
	private $albumMapper;

	/**
	 * Any-compliant image mapper
	 * 
	 * @var \Photogallery\Storage\PhotoMapperInterface
	 */
	private $photoMapper;

	/**
	 * History manager
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * Image manager to deal with images removal
	 * 
	 * @var \Krystal\Image\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * Web page manager to handle slugs
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * State initialization
	 * 
	 * @param \Album\Storage\AlbumMapperInterface $albumMapper
	 * @param \Album\Storage\PhotoMapperInterface $photoMapper
	 * @param \Krystal\Image\ImageManagerInterface $imageManager
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @param \Menu\Service\MenuWidgetInterface $menuWidget Optional menu widget service
	 * @return void
	 */
	public function __construct(
		AlbumMapperInterface $albumMapper, 
		PhotoMapperInterface $photoMapper, 
		ImageManagerInterface $imageManager, 
		WebPageManagerInterface $webPageManager, 
		HistoryManagerInterface $historyManager,
		MenuWidgetInterface $menuWidget = null
	){
		$this->albumMapper = $albumMapper;
		$this->photoMapper = $photoMapper;
		$this->imageManager = $imageManager;
		$this->webPageManager = $webPageManager;
		$this->historyManager = $historyManager;
		$this->setMenuWidget($menuWidget);
	}

	/**
	 * Defined by Menu\Contract\MenuAwareManager
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function fetchTitleByWebPageId($webPageId)
	{
		return $this->albumMapper->fetchTitleByWebPageId($webPageId);
	}

	/**
	 * Fetches all albums
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->albumMapper->fetchAll();
	}

	/**
	 * Fetches dummy album entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy()
	{
		return $this->toEntity(array(
			'id' => null,
			'parent_id' => null,
			'lang_id' => null,
			'web_page_id' => null,
			'title' => null,
			'name' => null,
			'description' => null,
			'order' => null,
			'keywords' => null,
			'meta_description' => null,
			'seo' => true
		));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $album)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $album['id'])
			->setParentId((int) $album['parent_id'])
			->setWebPageId((int) $album['web_page_id'])
			->setTitle(Filter::escape($album['title']))
			->setName(Filter::escape($album['name']))
			->setDescription(Filter::escapeContent($album['description']))
			->setOrder((int) $album['order'])
			->setKeywords(Filter::escape($album['keywords']))
			->setSlug(Filter::escape($this->webPageManager->fetchSlugByWebPageId($album['web_page_id'])))
			->setUrl($this->webPageManager->surround($entity->getSlug(), $album['lang_id']))
			->setPermanentUrl('/module/photogallery/'.$entity->getId())
			->setMetaDescription(Filter::escape($album['meta_description']))
			->setSeo((bool) $album['seo']);

		return $entity;
	}

	/**
	 * Returns last album's id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->albumMapper->getLastId();
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->albumMapper->getPaginator();
	}

	/**
	 * Fetches all albums filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->albumMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Prepares raw input data before sending it to the mapper
	 * 
	 * @param array $input
	 * @return array
	 */
	private function prepareInput(array $input)
	{
		// When name is empty, then it needs to be taken from a title
		if (empty($input['title'])) {
			$input['title'] = $input['name'];
		}

		// Empty slug is always taken from a name
		if (empty($input['slug'])) {
			$input['slug']= $input['title'];
		}

		// It's time to make a string look like a slug
		$input['slug'] = $this->webPageManager->sluggify($input['slug']);

		return $input;
	}

	/**
	 * Adds an album
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);
		$input['web_page_id'] = '';

		if ($this->albumMapper->insert(ArrayUtils::arrayWithout($input, array('slug', 'menu')))) {

			$this->track('Album "%s" has been created', $input['title']);

			if ($this->webPageManager->add($this->getLastId(), $input['slug'], 'Photogallery', 'Photogallery:Album@showAction', $this->albumMapper)){
				// Do the work in case menu widget was injected
				if ($this->hasMenuWidget()) {
					$this->addMenuItem($this->webPageManager->getLastId(), $input['title'], $input);
				}
			}

		} else {

			return false;
		}
	}

	/**
	 * Updates an album
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$input = $this->prepareInput($input);
		$this->webPageManager->update($input['web_page_id'], $input['slug']);

		$this->track('Album "%s" has been updated', $input['title']);

		if ($this->hasMenuWidget()) {
			$this->updateMenuItem($input['web_page_id'], $input['title'], $input['menu']);
		}

		return $this->albumMapper->update(ArrayUtils::arrayWithout($input, array('slug', 'menu')));
	}

	/**
	 * Removes an album from web page collection
	 * 
	 * @param string $albumId
	 * @return boolean
	 */
	private function removeWebPage($albumId)
	{
		$webPageId = $this->albumMapper->fetchWebPageIdById($albumId);
		return $this->webPageManager->deleteById($webPageId);
	}

	/**
	 * Deletes a whole album by its id including all its photos
	 * 
	 * @param string $id Album's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$this->removeWebPage($id);

		// Grab all photos associated with target album id
		$photos = $this->photoMapper->fetchAllByAlbumId($id);

		// Do batch removal if album has at least one photo
		if (!empty($photos)) {
			foreach ($photos as $photo) {
				// Photo id that belong in current album
				$id = $photo['id'];

				$this->imageManager->delete($id);
				$this->photoMapper->deleteById($id);
			}
		}

		$name = Filter::escape($this->albumMapper->fetchNameById($id));

		// We, gotta write log firstly, because album still exists, so that we can fetch its name
		$this->track('The album "%s" has been removed', $name);

		// Now finally, remove the album
		$this->albumMapper->deleteById($id);
		$this->albumMapper->deleteAllByParentId($id);

		return true;
	}

	/**
	 * Fetches album entity by its id
	 * 
	 * @param string $id Album's id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->albumMapper->fetchById($id));
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
		return $this->historyManager->write('Photogallery', $message, $placeholder);
	}
}
