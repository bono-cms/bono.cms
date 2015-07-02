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

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Photogallery\Storage\PhotoMapperInterface;
use Photogallery\Storage\AlbumMapperInterface;
use Krystal\Image\Tool\ImageManagerInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;

final class PhotoManager extends AbstractManager implements PhotoManagerInterface
{
	/**
	 * Any compliant photo mapper
	 * 
	 * @var \Photogallery\Storage\PhotoMapperInterface
	 */
	private $photoMapper;

	/**
	 * Any compliant album mapper
	 * 
	 * @var \Photogallery\Storage\AlbumMapperInterface
	 */
	private $albumMapper;

	/**
	 * Handles removal, uploading and building image paths
	 * 
	 * @var \Krystal\Image\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * History manager is used to keep track of common actions
	 * 
	 * @var \Photogallery\Storage\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Photogallery\Storage\PhotoMapperInterface $photoMapper
	 * @param \Photogallery\Storage\AlbumMapperInterface $albumMapper
	 * @param \Krystal\Image\ImageManagerInterface $imageManager
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(
		PhotoMapperInterface $photoMapper, 
		AlbumMapperInterface $albumMapper, 
		ImageManagerInterface $imageManager,
		HistoryManagerInterface $historyManager
	){
		$this->photoMapper  = $photoMapper;
		$this->albumMapper  = $albumMapper;
		$this->imageManager = $imageManager;
		$this->historyManager = $historyManager;
	}

	/**
	 * Returns breadcrumbs
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $page
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $page)
	{
		return array(
			array(
				'name' => $page->getTitle(),
				'link' => '#'
			)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $photo)
	{
		$imageBag = clone $this->imageManager->getImageBag();
		$imageBag->setId((int) $photo['id'])
				 ->setCover($photo['photo']);

		$entity = new VirtualEntity();
		$entity->setImageBag($imageBag)
				 ->setId((int) $photo['id'])
				 ->setName(Filter::escape($photo['name']))
				 ->setAlbumId((int) $photo['album_id'])
				 ->setAlbumName(Filter::escape($this->albumMapper->fetchNameById($photo['album_id'])))
				 ->setPhoto(Filter::escape($photo['photo']))
				 ->setDescription(Filter::escape($photo['description']))
				 ->setOrder((int) $photo['order'])
				 ->setPublished((bool) $photo['published']);

		return $entity;
	}

	/**
	 * Fetches random published photo
	 * 
	 * @param string $albumId Optionally can be filtered by album id
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function fetchRandomPublished($albumId = null)
	{
		if ($albumId !== null) {
			$data = $this->photoMapper->fetchRandomPublishedByAlbumId($albumId);
		} else {
			$data = $this->photoMapper->fetchRandomPublished();
		}

		return $this->prepareResult($data);
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
			if (!$this->photoMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Update orders by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateOrders(array $pair)
	{
		foreach ($pair as $id => $order) {
			if (!$this->photoMapper->updateOrderById($id, $order)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Deletes a photo by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	private function delete($id)
	{
		return $this->imageManager->delete($id) && $this->photoMapper->deleteById($id);
	}

	/**
	 * Removes photos by their associated ids
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
		
		$this->track('Batch removal of %s photos', count($ids));
		return true;
	}

	/**
	 * Removes a photo by its associated id
	 * 
	 * @param string $id Photo's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$name = Filter::escape($this->photoMapper->fetchNameById($id));

		if ($this->delete($id)) {

			$this->track('The photo "%s" has been removed', $name);
			return true;

		} else {

			return false;
		}
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->photoMapper->getPaginator();
	}

	/**
	 * Returns last photo id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->photoMapper->getLastId();
	}

	/**
	 * Prepares a container before sending to a mapper
	 * 
	 * @param array $input Raw input data
	 * @return array
	 */
	private function prepareInput(array $input)
	{
		$data =& $input['data'];
		$file =& $input['files']['file'];

		// Empty photo name should be replace by target filename
		if (empty($data['name'])) {
			$data['name'] = pathinfo($file[0]->getName(), \PATHINFO_FILENAME);
		}

		$this->filterFileInput($file);

		return $input;
	}

	/**
	 * Adds a photo
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);

		$data =& $input['data'];
		$file =& $input['files']['file'];

		$data['photo'] = $file[0]->getName();

		$this->track('A new photo "%s" has been uploaded', $data['name']);

		// Insert must be first, so that we can get the last id
		return $this->photoMapper->insert($data) && $this->imageManager->upload($this->getLastId(), $file);
	}

	/**
	 * Updates a photo
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$data =& $input['data'];

		// Upload a photo if present and override it
		if (!empty($input['files'])) {
			$input = $this->prepareInput($input);

			$file =& $input['files']['file'];

			// First of all, we need to remove old photo on the file-system
			if ($this->imageManager->delete($data['id'], $data['photo'])) {

				// And now upload a new one
				$data['photo'] = $file[0]->getName();
				$this->imageManager->upload($data['id'], $file);

			} else {

				return false;
			}
		}

		$this->track('The photo "%s" has been updated', $data['name']);
		return $this->photoMapper->update($data);
	}

	/**
	 * Fetches a photo bag by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->photoMapper->fetchById($id));
	}

	/**
	 * Fetches all photos filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->photoMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Fetches all photos associated with album id
	 * 
	 * @param string $albumId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByAlbumIdAndPage($albumId, $page, $itemsPerPage)
	{
		return $this->prepareResults($this->photoMapper->fetchAllByAlbumIdAndPage($albumId, $page, $itemsPerPage));
	}

	/**
	 * Fetches only published photos
	 * 
	 * @param string $albumId
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByAlbumIdAndPage($albumId, $page, $itemsPerPage)
	{
		return $this->prepareResults($this->photoMapper->fetchAllPublishedByAlbumIdAndPage($albumId, $page, $itemsPerPage));
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
