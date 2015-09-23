<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Storage\MySQL;

use Search\Storage\MySQL\AbstractSearchProvider;
use Krystal\Db\Sql\QueryBuilderInterface;

final class SearchMapper extends AbstractSearchProvider
{
	/**
	 * {@inheritDoc}
	 */
	public function appendQuery(QueryBuilderInterface $queryBuilder, $placeholder)
	{
		$queryBuilder->select($this->getWithDefaults(array('introduction' => 'content')))
					 ->from(PostMapper::getTableName())
					 ->whereEquals('lang_id', "'{$this->getLangId()}'")
					 ->andWhereEquals('published', '1')
					 ->andWhereLike('title', $placeholder)
					 ->orWhereLike('full', $placeholder);
	}
}
