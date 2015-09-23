<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
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
	 * Adds an advice
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates an advice
	 * 
	 * @param array $input Raw input data
	 * @return boolean Depending on success
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}
}
