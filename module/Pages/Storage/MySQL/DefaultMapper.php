<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Pages\Storage\DefaultMapperInterface;

final class DefaultMapper extends AbstractMapper implements DefaultMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_pages_defaults';
	}

	/**
	 * Updates a page's default id by its associated id
	 * 
	 * @param string $id Page id
	 * @return boolean
	 */
	public function update($id)
	{
		return $this->db->update(static::getTableName(), array('id' => $id))
						->whereEquals('lang_id', $this->getLangId())
						->execute();
	}

	/**
	 * Inserts a new page id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function insert($id)
	{
		return $this->db->insert(static::getTableName(), array(
			'id' => $id,
			'lang_id' => $this->getLangId()
		))->execute();
	}

	/**
	 * Checks whether given language id has a default page id
	 * 
	 * @return boolean
	 */
	public function exists()
	{
		return $this->db->select()
						->count('id', 'count')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->query('count') != 0;
	}

	/**
	 * Fetches all defaults
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->queryAll();
	}

	/**
	 * Fetches a default page id by the current mapper's language
	 * 
	 * @return string
	 */
	public function fetchDefaultId()
	{
		return $this->db->select('id')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->query('id');
	}

	/**
	 * Fetches default data by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchById($id)
	{
		return $this->db->select('id')
						->from(static::getTableName())
						->whereEquals('id', $id)
						->query('id');
	}
}
