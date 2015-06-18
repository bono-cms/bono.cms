<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Banner\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Banner\Storage\BannerMapperInterface;

final class BannerMapper extends AbstractMapper implements BannerMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_banner';

	/**
	 * Fetches banner name by its associated id
	 * 
	 * @param string $id Banner's id
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
	 * Updates a banner
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function update(array $input)
	{
		return $this->db->update($this->table, array(

			'name'	=> $input['name'],
			'link'	=> $input['link'],
			'image'	=> $input['image'],

		))->whereEquals('id', $input['id'])
		  ->execute();
	}

	/**
	 * Adds a banner
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(

			'lang_id'	=> $this->getLangId(),
			'name'		=> $input['name'],
			'link'		=> $input['link'],
			'image'		=> $input['image'],

		))->execute();
	}

	/**
	 * Fetches all banners filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param string $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches banner's data by its associated id
	 * 
	 * @param string $id Banner id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('id', $id)
						->query();
	}

	/**
	 * Deletes a banner by its associated id
	 * 
	 * @param string $id Banner's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('id', $id)
						->execute();
	}
}
