<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Cms\Storage\LanguageMapperInterface;

final class LanguageMapper extends AbstractMapper implements LanguageMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_cms_languages';

	/**
	 * Updates a column by its associated id with new value
	 * 
	 * @param string $id Language id
	 * @param string $column
	 * @param string $value
	 * @return boolean
	 */
	private function updateColumnById($id, $column, $value)
	{
		return $this->db->update($this->table, array($column => $value))
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Updates published state by its associated language id
	 * 
	 * @param string $id Language id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnById($id, 'published', $published);
	}

	/**
	 * Updates an order by its associated id
	 * 
	 * @param string $id Language id
	 * @param string $order New order
	 * @return boolean
	 */
	public function updateOrderById($id, $order)
	{
		return $this->updateColumnById($id, 'order', $order);
	}

	/**
	 * Fetches language data by its associated id
	 * 
	 * @param string $id Language id
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
	 * Fetches language data by its associated code
	 * 
	 * @param string $code Language's code
	 * @return array
	 */
	public function fetchByCode($code)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('code', $code)
						->query();
	}

	/**
	 * Deletes a language by its associated id
	 * 
	 * @param string $id Language id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Fetches all published languages
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('published', '1')
						->queryAll();
	}

	/**
	 * Fetches all languages
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->db->select('*')
						->from($this->table)
						->orderBy('id')
						->desc()
						->queryAll();
	}

	/**
	 * Inserts language data
	 * 
	 * @param array $data Language data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(
			
			'name' => $data['name'],
			'code' => $data['code'],
			'flag' => $data['flag'],
			'order' => $data['order'],
			'published' => $data['published'],
			
		))->execute();
	}

	/**
	 * Updates a language
	 * 
	 * @param array $data Language data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(
			
			'name' => $data['name'],
			'code' => $data['code'],
			'flag' => $data['flag'],
			'order' => $data['order'],
			'published' => $data['published']
			
		))->whereEquals('id', $data['id'])
		  ->execute();
	}
}
