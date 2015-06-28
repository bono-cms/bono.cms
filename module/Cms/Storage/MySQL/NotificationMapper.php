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
use Cms\Storage\NotificationMapperInterface;

final class NotificationMapper extends AbstractMapper implements NotificationMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_cms_notifications';
	}

	/**
	 * Clears all notifications
	 * 
	 * @return void
	 */
	public function clearAll()
	{
		return $this->db->truncate(static::getTableName())
						->execute();
	}

	/**
	 * Fetch all records filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from(static::getTableName())
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Makes all notifications as read
	 * 
	 * @return boolean
	 */
	public function nullify()
	{
		return $this->db->update(static::getTableName(), array('viewed' => '1'))
						->execute();
	}

	/**
	 * Counts all not viewed records
	 * 
	 * @return integer
	 */
	public function countUnviewed()
	{
		return $this->db->select()
						->count('id', 'count')
						->from(static::getTableName())
						->whereEquals('viewed', '0')
						->query('count');
	}

	/**
	 * Inserts a notification
	 * 
	 * @param string $timestamp
	 * @param string $viewed Either 0 or 1
	 * @param string $message
	 * @return boolean
	 */
	public function insert($timestamp, $viewed, $message)
	{
		return $this->db->insert(static::getTableName(), array(

			'timestamp'	=> $timestamp,
			'viewed'	=> $viewed,
			'message'	=> $message
			
		))->execute();
	}

	/**
	 * Deletes a notification by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}
}
