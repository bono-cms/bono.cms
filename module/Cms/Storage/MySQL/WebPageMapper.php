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
use Cms\Storage\WebPageMapperInterface;

final class WebPageMapper extends AbstractMapper implements WebPageMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_cms_webpages';

	/**
	 * Fetches language id by associated web page id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchLangIdByWebPageId($id)
	{
		return $this->db->select('lang_id')
						->from($this->table)
						->whereEquals('id', $id)
						->query('lang_id');
	}

	/**
	 * Fetches web page's slug by its associated id
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function fetchSlugByWebPageId($webPageId)
	{
		return $this->db->select('slug')
						->from($this->table)
						->whereEquals('id', $webPageId)
						->query('slug');
	}

	/**
	 * Fetches all web pages
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->queryAll();
	}

	/**
	 * Updates a web page
	 * 
	 * @param string $id Web page identification
	 * @param string $slug Web page's new slug
	 * @param string $controller Optionally controller can be updated too
	 * @return boolean
	 */
	public function update($id, $slug, $controller = null)
	{
		$data = array(
			'slug' => $slug
		);
		
		if ($controller !== null) {
			$data = array_merge($data, array('controller' => $controller));
		}
		
		return $this->db->update($this->table, $data)
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Inserts web page's data
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		$data = array_merge(array(
			'lang_id' => $this->getLangId(),
		), $data);

		return $this->db->insert($this->table, $data)
						->execute();
	}

	/**
	 * Fetches web page's data by associated slug
	 * 
	 * @param string $slug
	 * @return array
	 */
	public function fetchBySlug($slug)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('slug', $slug)
						->query();
	}

	/**
	 * Fetches web page's data by target id
	 * 
	 * @param string $targetId
	 * @return array
	 */
	public function fetchSlugByTargetId($targetId)
	{
		return $this->db->select('slug')
						->from($this->table)
						->whereEquals('target_id', $targetId)
						->query('slug');
	}

	/**
	 * Fetches web page's data by its associated id
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

	/**
	 * Deletes a web page by its associated id
	 * 
	 * @param string $id Web page id
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
