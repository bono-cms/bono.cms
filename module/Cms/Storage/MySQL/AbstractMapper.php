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

use Krystal\Db\Sql\AbstractMapper as BaseMapper;

abstract class AbstractMapper extends BaseMapper
{
	/**
	 * Target table we're dealing with
	 * 
	 * @var string
	 */
	protected $table;

	/**
	 * Language identification
	 * 
	 * @var string
	 */
	protected $language;

	/**
	 * Sets language id belonging to a mapper
	 * 
	 * @return \Cms\Storage\MySQL\AbstractMapper
	 */
	final public function setLangId($language)
	{
		$this->language = $language;
		return $this;
	}

	/**
	 * Returns last id
	 * 
	 * @return string
	 */
	final public function getLangId()
	{
		return $this->language;
	}

	/**
	 * Returns last id from a table
	 * 
	 * @param string $column
	 * @return integer
	 */
	final public function getLastId($column = 'id')
	{
		return $this->db->select()
						->max($column, 'last')
						->from($this->table)
						->query('last');
	}

	/**
	 * Fetches web page id by provided target id
	 * 
	 * @param string $id
	 * @return integer
	 */
	final public function fetchWebPageIdById($id)
	{
		return $this->db->select('web_page_id')
						->from($this->table)
						->whereEquals('id', $id)
						->query('web_page_id');
	}

	/**
	 * Fetches web page title by its associated id
	 * 
	 * @param string $webPageId Target web page id
	 * @return string
	 */
	final public function fetchTitleByWebPageId($webPageId)
	{
		return $this->db->select('title')
						->from($this->table)
						->whereEquals('web_page_id', $webPageId)
						->query('title');
	}

	/**
	 * Updates web page with a new one
	 * 
	 * @param string $id Target id
	 * @param string $webPageId Web page id
	 * @return boolean
	 */
	final public function updateWebPageIdById($id, $webPageId)
	{
		return $this->db->update($this->table, array('web_page_id' => $webPageId))
						->whereEquals('id', $id)
						->execute();
	}
}
