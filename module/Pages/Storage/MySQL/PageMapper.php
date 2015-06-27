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
	public static function getTableName()
	{
		return 'bono_module_pages';
	}

	/**
	 * Inserts a page
	 * 
	 * @param array $data Page data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert(static::getTableName(), array(

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
		return $this->db->update(static::getTableName(), array(

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
		return $this->updateColumnByPk($id, 'seo', $seo);
	}

	/**
	 * Fetches web page id by associated page id
	 * 
	 * @param string $id Page $id
	 * @return string
	 */
	public function fetchWebPageIdByPageId($id)
	{
		return $this->findColumnByPk($id, 'web_page_id');
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
						->from(static::getTableName())
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
	 * @return string
	 */
	public function fetchTitleById($id)
	{
		return $this->findColumnByPk($id, 'title');
	}

	/**
	 * Fetches page data by its associated id
	 * 
	 * @param string $id Page id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Deletes a page by its associated id
	 * 
	 * @param string $id Page id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}
}
