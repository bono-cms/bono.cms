<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Storage\MySQL;

use Search\Storage\MySQL\AbstractSearchProvider;
use Krystal\Db\Sql\QueryBuilderInterface;

final class SearchMapper extends AbstractSearchProvider
{
	/**
	 * {@inheritDoc}
	 */
	public function appendQuery(QueryBuilderInterface $queryBuilder, $placeholder)
	{
		$queryBuilder->select($this->getWithDefaults(array('description' => 'content')))
					 ->from(ProductMapper::getTableName())
					 ->whereEquals('lang_id', "'{$this->getLangId()}'")
					 ->andWhereEquals('published', '1')
					 ->andWhereLike('title', $placeholder)
					 ->orWhereLike('description', $placeholder);
	}
}
