<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Search\Storage\SearchMapperInterface;
use Krystal\Db\Sql\QueryBuilderInterface;

final class SearchMapper extends AbstractMapper implements SearchMapperInterface
{
	/**
	 * All registered mappers
	 * 
	 * @var array
	 */
	private $mappers = array();

	const PARAM_QUERY_PLACEHOLDER = ':keyword';

	/**
	 * Appends a searchable mapper
	 * 
	 * @param \Search\Storage\MySQL\AbstractSearchProvider
	 * @return void
	 */
	public function append(AbstractSearchProvider $mapper)
	{
		array_push($this->mappers, $mapper);
	}

	/**
	 * Queries by a keyword in all attached mappers
	 * 
	 * @param string $keyword
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function queryAll($keyword, $page, $itemsPerPage)
	{
		$query = $this->getQuery($keyword, $page, $itemsPerPage);
		$result = $this->getPdoStmt($query, $keyword)->fetchAll();

		// Clear all previous query data
		$this->db->getQueryBuilder()->clear();

		return $result;
	}

	/**
	 * Appends query parts from registered mappers
	 * 
	 * @param \Krystal\Db\Sql\QueryBuilderInterface $db Query builder
	 * @return void
	 */
	private function appendFromMappers(QueryBuilderInterface $qb)
	{
		// Amount of registered mappers we have
		$amount = count($this->mappers);

		// Iteration counter
		$i = 0;

		foreach ($this->mappers as $mapper) {

			$qb->openBracket();
			$mapper->appendQuery($qb, self::PARAM_QUERY_PLACEHOLDER);
			$qb->closeBracket();

			++$i;

			// Comparing iteration against number of mappers, tells whether this iteration is last
			$last = $i == $amount;

			// If we have more that one mapper, then we need to union results
			// And also, we should never append UNION in last iteration
			if ($amount > 1 && !$last) {
				$qb->union();
			}
		}
	}

	/**
	 * Returns prepared PDO statement object
	 * 
	 * @param string $query
	 * @param string $keyword
	 * @return \PDOStatement
	 */
	private function getPdoStmt($query, $keyword)
	{
		$pdo = $this->db->getPdo();

		$stmt = $pdo->prepare($query);
		$stmt->execute(array(
			self::PARAM_QUERY_PLACEHOLDER => '%'.$keyword.'%'
		));

		return $stmt;
	}

	/**
	 * Returns query that counts amount of results
	 * 
	 * @return string
	 */
	private function getCountQuery()
	{
		$qb = $this->db->getQueryBuilder();

		// First parts
		$qb->select()
		   ->count('id', 'count')
		   ->from()
		   ->openBracket();

		$this->appendFromMappers($qb);

		$qb->closeBracket()
		   ->asAlias('result');

		return $qb->getQueryString();
	}

	/**
	 * Counts all matches
	 * 
	 * @param $keyword Target string we're searching for
	 * @return integer
	 */
	private function countAll($keyword)
	{
		$result = $this->getPdoStmt($this->getCountQuery(), $keyword)->fetch();
		return (int) $result['count'];
	}

	/**
	 * Builds one query for all attached mappers
	 * 
	 * @param string $keyword
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return string Prepared query string
	 */
	private function getQuery($keyword, $page, $itemsPerPage)
	{
		// First of all, we need to tweak pagination
		$this->paginator->tweak($this->countAll($keyword), $itemsPerPage, $page);

		// Just a reference
		$qb = $this->db->getQueryBuilder();

		// Clear all previous query data
		$qb->clear();

		// First parts
		$qb->select('*')
		   ->from()
		   ->openBracket();

		$this->appendFromMappers($qb);

		$qb->closeBracket()
		   ->asAlias('result')
		   ->orderBy('id')
		   ->desc()
		   ->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage());
		
		return $qb->getQueryString();
	}
}
