<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Service;

use Krystal\Security\Filter;
use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Slider\Storage\ImageMapperInterface;
use Slider\Storage\CategoryMapperInterface;
use Slider\Service\ImageManagerFactory;

final class ImageManager extends AbstractManager implements ImageManagerInterface
{
	/**
	 * Any-compliant image mapper
	 * 
	 * @var \Slider\Storage\ImageMapperInterface
	 */
	private $imageMapper;

	/**
	 * Any-compliant category mapper
	 *
	 * @var \Slider\Storage\CategoryMapperInterface
	 */
	private $categoryMapper;

	/**
	 * Image manager to deal with images
	 * 
	 * @var \Krystal\Image\Tool\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * Factory which can be build image manager with different dimensions
	 * 
	 * @var \Slider\Service\ImageManagerFactory
	 */
	private $imageManagerFactory;

	/**
	 * History manager to keep track
	 * 
	 * @var \Cms\Service\HistoryManager
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Slider\Storage\ImageMapperInterface $imageMapper
	 * @param \Slider\Storage\CategoryMapperInterface $categoryMapper
	 * @param \Slider\Service\Factories\ImageManagerFactory $imageManagerFactory
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(
		ImageMapperInterface $imageMapper, 
		CategoryMapperInterface $categoryMapper, 
		ImageManagerFactory $imageManagerFactory, 
		HistoryManagerInterface $historyManager
	){
		$this->imageMapper = $imageMapper;
		$this->categoryMapper = $categoryMapper;
		$this->imageManagerFactory = $imageManagerFactory;
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
		return $this->historyManager->write('Slider', $message, $placeholder);
	}

	/**
	 * Updates published state by associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair)
	{
		foreach ($pair as $id => $published) {
			if (!$this->imageMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Updates orders by associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateOrders(array $pair)
	{
		foreach ($pair as $id => $order) {
			if (!$this->imageMapper->updateOrderById($id, $order)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Returns prepared paginator instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->imageMapper->getPaginator();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $image)
	{
		$imageBag = clone $this->getUploader($image['category_id'])->getImageBag();
		$imageBag->setId($image['id'])
				 ->setCover($image['image']);

		$entity = new ImageEntity(false);
		$entity->setImageBag($imageBag)
			->setId((int) $image['id'])
			->setCategoryId((int) $image['category_id'])
			->setCategoryName(Filter::escape($this->categoryMapper->fetchNameById($image['category_id'])))
			->setName(Filter::escape($image['name']))
			->setDescription(Filter::escape($image['description']))
			->setOrder((int) $image['order'])
			->setPublished((bool) $image['published'])
			->setLink(Filter::escape($image['link']))
			->setCover(Filter::escape($image['image']));

		return $entity;
	}

	/**
	 * Fetch all images filtered by paginator
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->imageMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Fetch all by category and page
	 * 
	 * @param string $categoryId
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByCategoryAndPage($categoryId, $page, $itemsPerPage)
	{
		return $this->prepareResults($this->imageMapper->fetchAllByCategoryAndPage($categoryId, $page, $itemsPerPage));
	}

	/**
	 * Fetches all published slider bags by category id
	 * 
	 * @param string $id Category id
	 * @return array
	 */
	public function fetchAllPublishedByCategoryId($id)
	{
		return $this->prepareResults($this->imageMapper->fetchAllPublishedByCategoryId($id));
	}

	/**
	 * Fetches all published slide images in provided category class
	 * 
	 * @param string $class Category's class
	 * @return array
	 */
	public function fetchAllPublishedByCategoryClass($class)
	{
		// Get associated id, first
		$id = $this->categoryMapper->fetchIdByClass($class);
		return $this->fetchAllPublishedByCategoryId($id);
	}

	/**
	 * Fetches a record by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->imageMapper->fetchById($id));
	}

	/**
	 * Returns last id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->imageMapper->getLastId();
	}

	/**
	 * Deletes an image
	 * Wrapped into this method to avoid duplication
	 * 
	 * @param string $id Image id
	 * @param string $categoryId Can be provided to omit a look up
	 * @return boolean
	 */
	private function delete($id, $categoryId = null)
	{
		if (is_null($categoryId)) {
			// Grab a category id for uploader instance
			$categoryId = $this->imageMapper->fetchCategoryIdById($id);
		}

		if ($categoryId) {
			if ($this->imageMapper->deleteById($id) && $this->getUploader($categoryId)->delete($id)) {
				return true;
			} else {
				// Failed to remove a file
				return false;
			}

		} else {

			// Invalid id supplied
			return false;
		}
	}

	/**
	 * Removes all images associated with given category id
	 * 
	 * @param string $categoryId
	 * @return boolean
	 */
	public function deleteAllByCategoryId($categoryId)
	{
		// Grab all ids associated with provided category id
		$ids = $this->imageMapper->fetchIdsByCategoryId($categoryId);

		// Start doing it when at least one id is present
		if (!empty($ids)) {
			foreach ($ids as $id) {
				if (!$this->delete($id, $categoryId)) {
					return false;
				}
			}
			
		}

		// @TODO: Now, it's time to remove records themselves
		//$this->imageMapper->deleteAllByCategoryId($categoryId);
		return true;
	}

	/**
	 * Deletes an image by its associated id
	 * 
	 * @param string $id Image's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$name = Filter::escape($this->imageMapper->fetchNameById($id));

		if ($this->delete($id)) {
			$this->track('Slider "%s" has been removed', $name);
			return true;

		} else {

			return false;
		}
	}

	/**
	 * Delete bunch of images by their ids
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

		$this->track('Batch removal of "%s" slides', count($ids));
		return true;
	}

	/**
	 * Builds uploader instance for a category
	 * 
	 * @param string $id Category id
	 * @return \Krystal\Image\Tool\ImageManager
	 */
	private function getUploader($id)
	{
		if (is_null($this->imageManager)) {
			// Grab dimensions
			$category = $this->categoryMapper->fetchById($id);
			
			if (!empty($category)) {
				// Define dimensions for this category
				$this->imageManagerFactory->setWidth($category['width'])
										  ->setHeight($category['height']);
			}
			
			$this->imageManager = $this->imageManagerFactory->build();
		}
		
		return $this->imageManager;
	}

	/**
	 * Prepares a container before sending to a mapper
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	private function prepareInput(array $input)
	{
		// Just references
		$data =& $input['data'];
		$file =& $input['files']['file'];

		if (empty($data['name'])) {
			$data['name'] = pathinfo($file[0]->getName(), \PATHINFO_FILENAME);
		}

		$this->filterFileInput($file);
		return $input;
	}

	/**
	 * Adds a slider
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		if (!empty($input['files'])) {
			$input = $this->prepareInput($input);

			$file =& $input['files']['file'];
			$data =& $input['data'];

			$data['image'] = $file[0]->getName();
			$uploader = $this->getUploader($data['category_id']);

			// Now insert to gain last id
			$this->imageMapper->insert($data);
			$this->track('Slider "%s" has been uploaded', $data['name']);

			return $uploader->upload($this->getLastId(), $file);
		}
	}

	/**
	 * Updates a slider
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$data =& $input['data'];

		// Handle image
		if (!empty($input['files'])) {
			$uploader = $this->getUploader($data['category_id']);

			// First of all, we need to remove old one
			if ($uploader->delete($data['id'], $data['image'])) {
				$input = $this->prepareInput($input);

				$file = $input['files']['file'];

				// Now override old image with a new one and start uploading
				$data['image'] = $file[0]->getName();
				$uploader->upload($data['id'], $file);

			} else {

				return false;
			}
		}

		$this->track('Slider "%s" has been updated', $data['name']);
		return $this->imageMapper->update($data);
	}
}
