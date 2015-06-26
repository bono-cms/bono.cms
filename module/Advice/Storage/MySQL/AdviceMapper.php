<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Advice\Storage\AdviceMapperInterface;

final class AdviceMapper extends AbstractMapper implements AdviceMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_advice';
	}

	/**
	 * Returns shared select
	 * 
	 * @param boolean $published
	 * @param boolean $rand Whether to select random record
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published, $rand = false)
	{
		$db = $this->db->select('*')
					   ->from(static::getTableName())
					   ->whereEquals('lang_id', $this->getLangId());

		if ($published === true) {
			$db->andWhereEquals('published', '1');
		}

		if ($rand === true) {

			$db->orderBy()
			   ->rand();

		} else {

			$db->orderBy('id')
			   ->desc();
		}

		return $db;
	}

	/**
	 * Updates published state by advice's associated id
	 * 
	 * @param string $id Advice id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnByPk($id, 'published', $published);
	}

	/**
	 * Fetches a random published advice
	 * 
	 * @return array
	 */
	public function fetchRandom()
	{
		return $this->getSelectQuery(true, true)
					->limit(1)
					->query();
	}

	/**
	 * Fetches all advices filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->getSelectQuery(false)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches all published advice filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->getSelectQuery(true)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches all advices
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->getSelectQuery(false)
					->queryAll();
	}

	/**
	 * Fetches an advice by its associated id
	 * 
	 * @param string $id Advice id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetches advice's title by its associated id
	 * 
	 * @param string $id Advice id
	 * @return string
	 */
	public function fetchTitleById($id)
	{
		return $this->findColumnByPk($id, 'title');
	}

	/**
	 * Deletes an advice by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Inserts an advice
	 * 
	 * @param string $title
	 * @param string $content
	 * @param string $published
	 * @return boolean
	 */
	public function insert($title, $content, $published)
	{
		return $this->db->insert(static::getTableName(), array(
			
			'lang_id'	=> $this->getLangId(),
			'title'		=> $title,
			'content'	=> $content,
			'published'	=> $published,
			
		))->execute();
	}

	/**
	 * Updates an advice
	 * 
	 * @param string $id
	 * @param string $title
	 * @param string $content
	 * @param string $published
	 * @return boolean Depending on success
	 */
	public function update($id, $title, $content, $published)
	{
		return $this->db->update(static::getTableName(), array(
			
			'title'	=> $title,
			'content'	=> $content,
			'published' => $published,
			
		))->whereEquals('id', $id)
		  ->execute();
	}
}
