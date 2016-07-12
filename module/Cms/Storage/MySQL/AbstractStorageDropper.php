<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Storage\MySQL;

use Krystal\Db\Sql\AbstractMapper;

abstract class AbstractStorageDropper extends AbstractMapper
{
    /**
     * Returns a collection of tables to be dropped
     * 
     * @return array
     */
    abstract protected function getTables();

    /**
     * Drop all tables
     * 
     * @return boolean
     */
    public function dropAll()
    {
        return $this->dropTables($this->getTables());
    }
}
