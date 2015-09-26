<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Reviews\Storage\ReviewsMapperInterface;

final class ReviewsMapper extends AbstractMapper implements ReviewsMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_reviews';
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
	 * Fetches review author's name by associated id
	 * 
	 * @param string $id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
	}

	/**
	 * Updates review published state by its associated id
	 * 
	 * @param string $id Review id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		$data = array(
			'published' => $published,
			'id' => $id
		);

		return $this->persist($data);
	}

	/**
	 * Adds a review
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates a review
	 * 
	 * @param array $input Review data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}

	/**
	 * Fetches all published reviews filtered by pagination
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
	 * Fetches all published reviews filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllPublishedByPage($page, $itemsPerPage)
	{
		return $this->getSelectQuery(true)
					->orderBy(array('timestamp' => 'DESC', 'id' => 'DESC'))
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches review data by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Deletes a review by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}
}
