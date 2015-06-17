<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Cms\Contract\WebPageMapperAwareInterface;
use Pages\Storage\PageMapperInterface;

final class PageMapper extends AbstractMapper implements PageMapperInterface, WebPageMapperAwareInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_pages';

	/**
	 * Inserts a page
	 * 
	 * @param array $data Page data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(

			'lang_id'			=> $this->getLangId(),
			'web_page_id'		=> $data['web_page_id'],
			'template'			=> $data['template'],
			'protected'			=> $data['protected'],
			'title'   			=> $data['title'],
			'content'  			=> $data['content'],
			'seo'				=> $data['seo'],
			'keywords'			=> $data['keywords'],
			'meta_description'	=> $data['meta_description'],

		))->execute();
	}

	/**
	 * Updates a page
	 * 
	 * @param array $data Page data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update($this->table, array(

			'template'			=> $data['template'],
			'protected'			=> $data['protected'],
			'title'				=> $data['title'],
			'content'			=> $data['content'],
			'seo'				=> $data['seo'],
			'keywords'			=> $data['keywords'],
			'meta_description'	=> $data['meta_description']

		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Updates whether SEO should be enabled or not
	 * 
	 * @param string $id Page id
	 * @param string $seo Either 0 or 1
	 * @return boolean
	 */
	public function updateSeoById($id, $seo)
	{
		return $this->db->update($this->table, array('seo' => $seo))
						->whereEquals('id', $id)
						->execute();
	}

	/**
	 * Fetches web page id by associated page id
	 * 
	 * @param string $id Page $id
	 * @return string
	 */
	public function fetchWebPageIdByPageId($id)
	{
		return $this->db->select('web_page_id')
						->from($this->table)
						->whereEquals('id', $id)
						->query('web_page_id');
	}

	/**
	 * Fetches all pages filtered by pagination
	 * 
	 * @param string $page Current page id
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
	 * Fetches page title by its associated id
	 * 
	 * @param string $id Page id
	 * @retunr string
	 */
	public function fetchTitleById($id)
	{
		return $this->db->select('title')
						->from($this->table)
						->whereEquals('id', $id)
						->query('title');
	}

	/**
	 * Fetches page data by its associated id
	 * 
	 * @param string $id Page id
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
	 * Deletes a page by its associated id
	 * 
	 * @param string $id Page id
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
