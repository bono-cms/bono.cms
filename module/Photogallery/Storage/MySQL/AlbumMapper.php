<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Photogallery\Storage\AlbumMapperInterface;

final class AlbumMapper extends AbstractMapper implements AlbumMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_photoalbum_albums';
	}

	/**
	 * Returns shared select
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery()
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc();
	}

	/**
	 * Fetches a record by its id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetch all records filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Items Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->getSelectQuery()
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches all albums
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->getSelectQuery()
					->queryAll();
	}

	/**
	 * Fetches album name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
	}

	/**
	 * Deletes an album by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Deletes all albums children by associated parent id
	 * 
	 * @param integer $parentId
	 * @return boolean
	 */
	public function deleteAllByParentId($parentId)
	{
		return $this->deleteByColumn('parent_id', $parentId);
	}

	/**
	 * Adds an album
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates an album
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}
}
