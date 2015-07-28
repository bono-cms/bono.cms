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
	 * Fetches message view by associated form id
	 * 
	 * @param string $id Form id
	 * @return string
	 */
	public function fetchMessageViewById($id)
	{
		return $this->findColumnByPk($id, 'message_view');
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
	 * @param array $input
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates a form
	 * 
	 * @param array $input
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
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
