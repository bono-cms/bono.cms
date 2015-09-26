<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Qa\Storage\QaMapperInterface;

final class QaMapper extends AbstractMapper implements QaMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_qa';
	}

	/**
	 * Returns shared select
	 * 
	 * @param boolean $published
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published)
	{
		$db = $this->db->select('*')
					   ->from(static::getTableName())
					   ->whereEquals('lang_id', $this->getLangId());

		if ($published === true) {
			$db->andWhereEquals('published', '1');
		}

		return $db;
	}

	/**
	 * Fetches all published QA pairs filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->getSelectQuery(true)
					->orderBy(array('timestamp_asked' => 'DESC', 'id' => 'DESC'))
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches all published QA pairs
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->getSelectQuery(true)
					->queryAll();
	}

	/**
	 * Fetches all QA pairs filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->getSelectQuery(false)
					->orderBy('id')
					->desc()
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches question data by QA's associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchQuestionById($id)
	{
		return $this->findColumnByPk($id, 'question');
	}

	/**
	 * Fetches QA data by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Updates QA data
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}

	/**
	 * Adds QA pair
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates published state by QA's associated id
	 * 
	 * @param string $id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnByPk($id, 'published', $published);
	}

	/**
	 * Deletes QA pair by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}
}
