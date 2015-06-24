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
	protected $table = 'bono_module_photoalbum_albums';

	/**
	 * Returns shared select
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc();
	}

	/**
	 * Deletes all data by associated column and its value
	 * 
	 * @param string $column
	 * @param string $value
	 * @return boolean
	 */
	private function deleteAllByColumn($column, $value)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals($column, $value)
						->execute();
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
		return $this->db->select('name')
						->from($this->table)
						->whereEquals('id', $id)
						->query('name');
	}

	/**
	 * Deletes an album by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteAllByColumn('id', $id);
	}

	/**
	 * Deletes all albums children by associated parent id
	 * 
	 * @pamra integer $parentId
	 * @return boolean
	 */
	public function deleteAllByParentId($parentId)
	{
		return $this->deleteAllByColumn('parent_id', $parentId);
	}

	/**
	 * Adds an album
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(

			'lang_id'			=> $this->getLangId(),
			'parent_id' 		=> $input['parent_id'],
			'web_page_id'		=> '',
			'title'				=> $input['title'],
			'name'				=> $input['name'],
			'description'		=> $input['description'],
			'order'				=> $input['order'],
			'keywords'			=> $input['keywords'],
			'meta_description'	=> $input['meta_description'],
			'seo'				=> $input['seo']

		))->execute();
	}

	/**
	 * Updates an album
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->db->update($this->table, array(

			'parent_id' 	=> $input['parent_id'],
			'title'     	=> $input['title'],
			'name'			=> $input['name'],
			'description'	=> $input['description'],
			'order'			=> $input['order'],
			'keywords'		=> $input['keywords'],
			'meta_description' => $input['meta_description'],
			'seo'			=> $input['seo']

		))->whereEquals('id', $input['id'])
		  ->execute();
	}

	/**
	 * Fetches a record by its id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('id', $id)
						->query();
	}
}
