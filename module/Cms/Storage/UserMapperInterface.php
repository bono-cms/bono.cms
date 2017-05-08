<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Storage;

interface UserMapperInterface
{
    /**
     * Determines whether email already exists
     * 
     * @param string $email
     * @return boolean
     */
    public function emailExists($email);

    /**
     * Determines whether login already exists
     * 
     * @param string $login
     * @return boolean
     */
    public function loginExists($login);

    /**
     * Inserts user's data
     * 
     * @param array $data
     * @return boolean
     */
    public function insert(array $data);

    /**
     * Updates user's data
     * 
     * @param array $data
     * @return boolean
     */
    public function update(array $data);

    /**
     * Fetches user's name by associated id
     * 
     * @param string $id User id
     * @return string
     */
    public function fetchNameById($id);

    /**
     * Fetches by credentials
     * 
     * @param string $login
     * @param string $passwordHash
     * @return array
     */
    public function fetchByCredentials($login, $passwordHash);

    /**
     * Fetches all users
     * 
     * @return array
     */
    public function fetchAll();

    /**
     * Fetches user's data by associated id
     * 
     * @param string $id User's id
     * @return array
     */
    public function fetchById($id);

    /**
     * Deletes a user by associated id
     * 
     * @param string $id User's id
     * @return boolean
     */
    public function deleteById($id);
}
