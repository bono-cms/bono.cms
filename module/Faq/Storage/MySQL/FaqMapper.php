<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Faq\Storage\FaqMapperInterface;

final class FaqMapper extends AbstractMapper implements FaqMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_faq';
	}

	/**
	 * Builds SELECT query
	 * 
	 * @param boolean $published Whether to filter by published records
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($published)
	{
		// Build first fragment
		$db = $this->db->select('*')
						->from(static::getTableName())
						->whereEquals('lang_id', $this->getLangId());

		if ($published === true) {
			$db->andWhereEquals('published', '1')
			   ->orderBy('order');
		} else {
			$db->orderBy('id')
			   ->desc();
		}

		return $db;
	}

	/**
	 * Fetches question name by its associated id
	 * 
	 * @param string $id FAQ id
	 * @return string
	 */
	public function fetchQuestionById($id)
	{
		return $this->findColumnByPk($id, 'question');
	}

	/**
	 * Update published state by its associated FAQ id
	 * 
	 * @param integer $id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnByPk($id, 'published', $published);
	}

	/**
	 * Update an order by record's associated id
	 * 
	 * @param string $id
	 * @param integer $order New sort order
	 * @return boolean
	 */
	public function updateOrderById($id, $order)
	{
		return $this->updateColumnByPk($id, 'order', $order);
	}

	/**
	 * Fetches all FAQs filtered by pagination
	 * 
	 * @param integer $page Current page number
	 * @param integer $itemsPerPage Per page count
	 * @param boolean $published Whether to fetch only published ones
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage, $published)
	{
		return $this->getSelectQuery($published)
					->paginate($page, $itemsPerPage)
					->queryAll();
	}

	/**
	 * Fetches all published FAQs
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->getSelectQuery(true)
					->queryAll();
	}

	/**
	 * Adds new FAQ
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates a FAQ
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($input);
	}

	/**
	 * Deletes a FAQ by its associated id
	 * 
	 * @param string $id FAQ's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Fetches FAQ's data by its associated id
	 * 
	 * @param string $id FAQ's id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}
}
