<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
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
use Krystal\Stdlib\ArrayUtils;
use Krystal\Image\Tool\ImageManagerInterface;
use Krystal\Security\Filter;
use Krystal\Tree\AdjacencyList\TreeBuilder;
use Krystal\Tree\AdjacencyList\BreadcrumbBuilder;

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
	 * Album photo manager
	 * 
	 * @var \Krystal\Image\ImageManagerInterface
	 */
	private $albumPhoto;

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
		ImageManagerInterface $albumPhoto,
		ImageManagerInterface $imageManager, 
		WebPageManagerInterface $webPageManager, 
		HistoryManagerInterface $historyManager,
		MenuWidgetInterface $menuWidget = null
	){
		$this->albumMapper = $albumMapper;
		$this->photoMapper = $photoMapper;
		$this->albumPhoto = $albumPhoto;
		$this->imageManager = $imageManager;
		$this->webPageManager = $webPageManager;
		$this->historyManager = $historyManager;
		$this->setMenuWidget($menuWidget);
	}

	/**
	 * Returns breadcrumbs
	 * 
	 * @param \Photogallery\Service\AlbumEntity $album
	 * @return array
	 */
	public function getBreadcrumbs(AlbumEntity $album)
	{
		return $this->getBreadcrumbsById($album->getId());
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
	 * Fetches children by parent id
	 * 
	 * @param string $parentId
	 * @return array
	 */
	public function fetchChildrenByParentId($parentId)
	{
		return $this->prepareResults($this->albumMapper->fetchChildrenByParentId($parentId));
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
	 * {@inheritDoc}
	 */
	protected function toEntity(array $album)
	{
		$imageBag = clone $this->albumPhoto->getImageBag();
		$imageBag->setId((int) $album['id'])
				 ->setCover($album['cover']);

		$entity = new AlbumEntity();
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
			->setSeo((bool) $album['seo'])
			->setCover($album['cover'])
			->setImageBag($imageBag);

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
		$form =& $input['data']['album'];

		// When name is empty, then it needs to be taken from a title
		if (empty($form['title'])) {
			$form['title'] = $form['name'];
		}

		// Empty slug is always taken from a name
		if (empty($form['slug'])) {
			$form['slug'] = $form['title'];
		}

		// It's time to make a string look like a slug
		$form['slug'] = $this->webPageManager->sluggify($form['slug']);

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
		$form =& $input['data']['album'];

		// Default empty values
		$form['web_page_id'] = '';
		$form['cover'] = '';

		// If we have a cover, then we need to upload it
		if (!empty($input['files']['file'])) {
			$file =& $input['files']['file'];
			$this->filterFileInput($file);

			// Override empty cover's value now
			$form['cover'] = $file[0]->getName();
		}

		if ($this->albumMapper->insert(ArrayUtils::arrayWithout($form, array('slug', 'menu')))) {
			$id = $this->getLastId();

			// If there's a file, then it needs to uploaded as a cover
			if (!empty($input['files']['file'])) {
				$this->albumPhoto->upload($id, $input['files']['file']);
			}

			$this->track('Album "%s" has been created', $form['title']);

			if ($this->webPageManager->add($this->getLastId(), $form['slug'], 'Photogallery', 'Photogallery:Album@showAction', $this->albumMapper)){
				// Do the work in case menu widget was injected
				if ($this->hasMenuWidget()) {
					$this->addMenuItem($this->webPageManager->getLastId(), $form['title'], $input);
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
		$form =& $input['data']['album'];

		// Allow to remove a cover, only it case it exists and checkbox was checked
		if (isset($form['remove_cover'])) {

			// Remove a cover, but not a dir itself
			$this->albumPhoto->delete($form['id']);
			$form['cover'] = '';

		} else {

			if (!empty($input['files']['file'])) {
				$file =& $input['files']['file'];

				// If we have a previous cover's image, then we need to remove it
				if (!empty($form['cover'])) {
					if (!$this->albumPhoto->delete($form['id'], $form['cover'])) {
						// If failed, then exit this method immediately
						return false;
					}
				}

				// And now upload a new one
				$this->filterFileInput($file);
				$form['cover'] = $file[0]->getName();

				$this->albumPhoto->upload($form['id'], $file);
			}
		}

		$this->webPageManager->update($form['web_page_id'], $form['slug']);
		$this->track('Album "%s" has been updated', $form['title']);

		if ($this->hasMenuWidget()) {
			$this->updateMenuItem($form['web_page_id'], $form['title'], $input['data']['menu']);
		}

		return $this->albumMapper->update(ArrayUtils::arrayWithout($form, array('slug', 'menu', 'remove_cover')));
	}

	/**
	 * Deletes a whole album by its id including all its photos
	 * 
	 * @param string $id Album's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		// Save the name into a variable, before an album is removed
		$name = Filter::escape($this->albumMapper->fetchNameById($id));

		// Do remove now
		$this->removeAlbumById($id);
		$this->removeChildAlbumsByParentId($id);

		$this->track('The album "%s" has been removed', $name);

		return true;
	}

	/**
	 * Removes child albums that belong to provided id
	 * 
	 * @param string $parentId
	 * @return boolean
	 */
	private function removeChildAlbumsByParentId($parentId)
	{
		$treeBuilder = new TreeBuilder($this->albumMapper->fetchAll());
		$ids = $treeBuilder->findChildNodeIds($parentId);

		// If there's at least one child id, then start working next
		if (!empty($ids)) {
			foreach ($ids as $id) {
				$this->removeAlbumById($id);
			}
		}

		return true;
	}

	/**
	 * Removes an album by its associated id
	 * 
	 * @param string $albumId
	 * @return boolean
	 */
	private function removeAlbumById($albumId)
	{
		$this->albumMapper->deleteById($albumId);
		$this->removeWebPage($albumId);

		// Grab all photos associated with target album id
		$photosIds = $this->photoMapper->fetchPhotoIdsByAlbumId($albumId);

		// Do batch removal if album has at least one photo
		if (!empty($photosIds)) {
			foreach ($photosIds as $photoId) {
				// Remove a photo
				$this->imageManager->delete($photoId) && $this->photoMapper->deleteById($photoId);
			}
		}

		$this->albumPhoto->delete($albumId);
		return true;
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

	/**
	 * Gets all breadcrumbs by associated id
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	private function getBreadcrumbsById($id)
	{
		$wm = $this->webPageManager;
		$builder = new BreadcrumbBuilder($this->albumMapper->fetchBcData(), $id);

		return $builder->makeAll(function($breadcrumb) use ($wm) {
			return array(
				'name' => $breadcrumb['title'],
				'link' => $wm->getUrl($breadcrumb['web_page_id'], $breadcrumb['lang_id'])
			);
		});
	}	
}
