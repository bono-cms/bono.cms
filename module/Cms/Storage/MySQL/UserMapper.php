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

use Cms\Storage\UserMapperInterface;
use Cms\Storage\MySQL\AbstractMapper;

final class UserMapper extends AbstractMapper implements UserMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_cms_users');
    }

    /**
     * Determines whether email already exists
     * 
     * @param string $email
     * @return boolean
     */
    public function emailExists($email)
    {
        return $this->valueExists('email', $email);
    }

    /**
     * Determines whether login already exists
     * 
     * @param string $login
     * @return boolean
     */
    public function loginExists($login)
    {
        return $this->valueExists('login', $login);
    }

    /**
     * Inserts user's data
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function insert(array $input)
    {
        return $this->persist($input);
    }

    /**
     * Updates user's data
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        return $this->persist($input);
    }

    /**
     * Fetches user's name by associated id
     * 
     * @param string $id User id
     * @return string
     */
    public function fetchNameById($id)
    {
        return $this->findColumnByPk($id, 'name');
    }

    /**
     * Fetches by credentials
     * 
     * @param string $login
     * @param string $passwordHash
     * @return array
     */
    public function fetchByCredentials($login, $passwordHash)
    {
        return $this->db->select('*')
                        ->from(self::getTableName())
                        ->whereEquals('login', $login)
                        ->andWhereEquals('password_hash', $passwordHash)
                        ->query();
    }

    /**
     * Fetches all users
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->db->select('*')
                        ->from(self::getTableName())
                        ->orderBy('id')
                        ->desc()
                        ->queryAll();
    }

    /**
     * Fetches user's data by associated id
     * 
     * @param string $id User's id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->findByPk($id);
    }

    /**
     * Remove all but provided
     * 
     * @param int $id User's id to be kept
     * @return boolean
     */
    public function wipe($id)
    {
        $db = $this->db->delete()
                       ->from(self::getTableName())
                       ->whereNotEquals('id', $id);

        return (bool) $db->execute(false);
    }

    /**
     * Deletes a user by associated id
     * 
     * @param string $id User's id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->deleteByPk($id);
    }
}
