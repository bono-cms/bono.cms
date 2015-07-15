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

use Krystal\Stdlib\VirtualEntity;

/**
 * API for Album Manager
 */
interface AlbumManagerInterface
{
	/**
	 * Returns breadcrumbs
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $album
	 * @return array
	 */
	public function getBreadcrumbs(VirtualEntity $album);

	/**
	 * Fetches all albums
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Returns last album's id
	 * 
	 * @return integer
	 */
	public function getLastId();

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator();

	/**
	 * Fetches all albums filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Adds an album
	 * 
	 * @param array $form Form data
	 * @return boolean
	 */
	public function add(array $form);

	/**
	 * Updates an album
	 * 
	 * @param array $form Form data
	 * @return boolean
	 */
	public function update(array $form);

	/**
	 * Deletes a whole album by its id including all its photos
	 * 
	 * @param string $id Album id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Fetches an album bag by its id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id);
}
