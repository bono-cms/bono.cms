<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use MailForm\Storage\FormMapperInterface;

final class FormMapper extends AbstractMapper implements FormMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_mailform';
	}

	/**
	 * Updates SEO state by form's associated id
	 * 
	 * @param string $id
	 * @param string $seo
	 * @return boolean
	 */
	public function updateSeoById($id, $seo)
	{
		return $this->updateColumnByPk($id, 'seo', $seo);
	}

	/**
	 * Adds new form
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert(static::getTableName(), array(

			'lang_id'		=> $this->getLangId(),
			'web_page_id'	=> '',
			'template'		=> $data['template'],
			'title'			=> $data['title'],
			'description'	=> $data['description'],
			'seo'			=> $data['seo'],
			'keywords'		=> $data['keywords'],
			'meta_description' => $data['metaDescription']
			
		))->execute();
	}

	/**
	 * Updates a form
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->db->update(static::getTableName(), array(

			'template' => $data['template'],
			'title' => $data['title'],
			'description' => $data['description'],
			'seo' => $data['seo'],
			'keywords' => $data['keywords'],
			'meta_description' => $data['metaDescription']

		))->whereEquals('id', $data['id'])
		  ->execute();
	}

	/**
	 * Fetches form data by its associated id
	 * 
	 * @param string $id Form's id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetches all forms
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
	 * Deletes a form by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}
}
