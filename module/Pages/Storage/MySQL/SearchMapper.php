<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Storage\MySQL;

use Search\Storage\MySQL\AbstractSearchProvider;
use Krystal\Db\Sql\QueryBuilderInterface;

final class SearchMapper extends AbstractSearchProvider
{
	/**
	 * {@inheritDoc}
	 */
	public function appendQuery(QueryBuilderInterface $queryBuilder, $placeholder)
	{
		$queryBuilder->select($this->getWithDefaults(array('content')))
					 ->from(PageMapper::getTableName())
					 ->whereEquals('seo', '1')
					 ->andWhereEquals('lang_id', "'{$this->getLangId()}'")
					 ->rawAnd()
					 ->openBracket()
					 ->like('title', $placeholder)
					 ->rawOr()
					 ->like('content', $placeholder)
					 ->closeBracket();
	}
}
