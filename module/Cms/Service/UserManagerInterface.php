<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

/**
 * API for User manager
 */
interface UserManagerInterface
{
    /**
     * Fetches user's name by associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchNameById($id);

    /**
     * Returns last added user's` id
     * 
     * @return integer
     */
    public function getLastId();

    /**
     * Adds a user
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input);

    /**
     * Updates a user
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input);
}
