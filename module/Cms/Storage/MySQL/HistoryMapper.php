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
use Cms\Storage\HistoryMapperInterface;

final class HistoryMapper extends AbstractMapper implements HistoryMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_cms_history';
	}

	/**
	 * Clears the history
	 * 
	 * @return boolean
	 */
	public function clear()
	{
		return $this->db->delete()
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->execute();
	}

	/**
	 * Inserts a history track
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert(static::getTableName(), array(

			'lang_id'	=> $this->getLangId(),
			'user_id'	=> $input['user_id'],
			'timestamp'	=> $input['timestamp'],
			'module'	=> $input['module'],
			'comment'	=> $input['comment'],
			'placeholder' => $input['placeholder']

		))->execute();
	}

	/**
	 * Fetches all history tracks filtered by pagination
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
}
