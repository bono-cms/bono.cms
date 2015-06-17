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
	protected $table = 'bono_module_cms_history';

	/**
	 * Clears the history
	 * 
	 * @return boolean
	 */
	public function clear()
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->execute();
	}

	/**
	 * Inserts a history track
	 * 
	 * @param array $data History data
	 * @return boolean
	 */
	public function insert(array $data)
	{
		return $this->db->insert($this->table, array(

			'lang_id'	=> $this->getLangId(),
			'user_id'	=> $data['user_id'],
			'timestamp'	=> $data['timestamp'],
			'module'	=> $data['module'],
			'comment'	=> $data['comment'],
			'placeholder' => $data['placeholder']

		))->execute();
	}

	/**
	 * Counts all history tracks
	 * 
	 * @return integer
	 */
	private function countAll()
	{
		return $this->db->select()
						->count('id', 'count')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->query('count');
	}

	/**
	 * Fetches all history tracks filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @parma integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		// Tweak paginator instance
		$this->paginator->setTotalAmount($this->countAll())
						->setItemsPerPage($itemsPerPage)
						->setCurrentPage($page);
		
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage())
						->queryAll();
	}
}
