<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Banner\Service;

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Banner\Storage\BannerMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;

final class BannerManager extends AbstractManager implements BannerManagerInterface
{
	/**
	 * Any compliant banner mapper
	 * 
	 * @var \Banner\Storage\BannerMapperInterface
	 */
	private $banerMapper;

	/**
	 * History Manager to track latest activity
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \Banner\Storage\BannerMapperInterface $banerMapper
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(BannerMapperInterface $bannerMapper, HistoryManagerInterface $historyManager)
	{
		$this->bannerMapper = $bannerMapper;
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
		return $this->historyManager->write('Banner', $message, $placeholder);
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->bannerMapper->getPaginator();
	}

	/**
	 * Returns last banner's id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->bannerMapper->getLastId();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $banner)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $banner['id'])
			->setName(Filter::escape($banner['name']))
			->setLink(Filter::escape($banner['link']))
			->setImage(Filter::escape($banner['image']));
		
		return $entity;
	}

	/**
	 * Fetches dummy banner's entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy()
	{
		return $this->toEntity(array(
			'id' => null,
			'name' => null,
			'link' => null,
			'image' => null,
		));
	}

	/**
	 * Fetches all banner entities filtered by pagination
	 * 
	 * @param string $page Current page
	 * @param string $itemsPerPage Per page count
	 * @return array Array of banner bags
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->bannerMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Prepares an input before sending it to a mapper
	 * 
	 * @param array $input Raw form data
	 * @return array
	 */
	private function prepareInput(array $input)
	{
		$file =& $input['files']['file'];
		$data =& $input['data'];

		if (!empty($file)) {
			$data['image'] = $file[0]->getName();
		}

		return $input;
	}

	/**
	 * Adds a banner
	 * 
	 * @param array $input Raw form input
	 * @return boolean
	 */
	public function add(array $form)
	{
		$form = $this->prepareInput($form);
		
		$data =& $form['data'];
		
		//@TODO: Here must be file uploading
		if (1) {
			
			// Trace this action
			$this->track('Banner "%s" has been uploaded', $data['name']);
			return $this->bannerMapper->insert($data);
		}
	}

	/**
	 * Updates a banner
	 * 
	 * @param array $input Raw form input
	 * @return boolean
	 */
	public function update(array $input)
	{
		$form = $this->prepareInput($input);
		$data =& $form['data'];
		
		// If we have a banner
		if (!empty($form['files'])) {
			// Then we need to remove old one
		}
		
		// Trace this move
		$this->track('Banner %s has been updated', $data['name']);
		return $this->bannerMapper->update($data);
	}

	/**
	 * Deletes a banner by its associated id
	 * 
	 * @param string $id Banner id
	 * @return boolean
	 */
	private function delete($id)
	{
		//@TODO File also
		return $this->bannerMapper->deleteById($id);
	}

	/**
	 * Deletes a banner by its associated id
	 * 
	 * @param string $id Banner id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$name = Filter::escape($this->bannerMapper->fetchNameById($id));

		if ($this->delete($id)) {
			$this->track('Banner "%s" has been removed', $name);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Delete banners by their associated ids
	 * 
	 * @param array $ids Array of banner ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			if (!$this->delete($id)) {
				return false;
			}
		}

		$this->track('Batch removal of %s banner', count($ids));
		return true;
	}

	/**
	 * Fetches banner's entity by its associated id
	 * 
	 * @param string $id Banner id
	 * @return boolean|\Krystal\Stdlib\VirtualEntity
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->bannerMapper->fetchById($id));
	}
}
