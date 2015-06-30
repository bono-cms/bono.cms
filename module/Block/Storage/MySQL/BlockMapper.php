<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Block\Storage\BlockMapperInterface;

final class BlockMapper extends AbstractMapper implements BlockMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_block';
	}

	/**
	 * Fetches block's content by its associated class name
	 * 
	 * @param string $class
	 * @return string
	 */
	public function fetchContentByClass($class)
	{
		return $this->db->select('content')
						->from(static::getTableName())
						->whereEquals('class', $class)
						->andWhereEquals('lang_id', $this->getLangId())
						->query('content');
	}

	/**
	 * Fetches block name by its associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
	}

	/**
	 * Fetches block data by its associated class name
	 * 
	 * @param string $class Block's class name
	 * @return array
	 */
	public function fetchByClass($class)
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->whereEquals('class', $class)
						->andWhereEquals('lang_id', $this->getLangId())
						->query();
	}

	/**
	 * Fetches all blocks
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->queryAll();
	}

	/**
	 * Fetches all blocks filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Fetches block data by its associated id
	 * 
	 * @param string $id Block id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Inserts block's data
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates block's data
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}

	/**
	 * Deletes a block by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}
}
