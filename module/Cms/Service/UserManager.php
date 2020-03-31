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

use Cms\Service\AbstractManager;
use Cms\Storage\UserMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Authentication\AuthManagerInterface;
use Krystal\Authentication\UserAuthServiceInterface;
use Krystal\Security\Filter;
use Krystal\Stdlib\ArrayUtils;

final class UserManager extends AbstractManager implements UserManagerInterface, UserAuthServiceInterface
{
    /**
     * Any compliant user mapper
     * 
     * @var \Cms\Storage\UserMapperInteface
     */
    private $userMapper;

    /**
     * Authorization manager
     * 
     * @var \Krystal\Authentication\AuthManagerInterface
     */
    private $authManager;

    /**
     * State initialization
     * 
     * @param \Cms\Storage\UserMapperInteface $userMapper Any mapper which implements this interface
     * @param \Krystal\Authentication\AuthManagerInterface $authManager
     * @return void
     */
    public function __construct(UserMapperInterface $userMapper, AuthManagerInterface $authManager)
    {
        $this->userMapper = $userMapper;
        $this->authManager = $authManager;
    }

    /**
     * Determines whether email already exists
     * 
     * @param string $email
     * @return boolean
     */
    public function emailExists($email)
    {
        return $this->userMapper->emailExists($email);
    }

    /**
     * Determines whether login already exists
     * 
     * @param string $login
     * @return boolean
     */
    public function loginExists($login)
    {
        return $this->userMapper->loginExists($login);
    }

    /**
     * Fetches user's name by associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchNameById($id)
    {
        // This method is called inside foreach, so we need a cache anyway
        static $cache = array();

        if (isset($cache[$id])) {
            // $cache[$id] represent user name
            return $cache[$id];

        } else {
            $name = $this->userMapper->fetchNameById($id);
            $cache[$id] = $name;

            return $name;
        }
    }

    /**
     * Returns last added user's` id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->userMapper->getLastId();
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $user)
    {
        $entity = new VirtualEntity();
        $entity->setId($user['id'], VirtualEntity::FILTER_INT)
            ->setLogin($user['login'], VirtualEntity::FILTER_HTML)
            ->setPasswordHash($user['password_hash'])
            ->setRole($user['role'], VirtualEntity::FILTER_HTML)
            ->setEmail($user['email'], VirtualEntity::FILTER_HTML)
            ->setName($user['name'], VirtualEntity::FILTER_HTML);

        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->authManager->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getRole()
    {
        return $this->authManager->getRole();
    }

    /**
     * Attempts to authenticate a user
     * 
     * @param string $login
     * @param string $password
     * @param boolean $remember Whether to remember
     * @param boolean $hash Whether to hash password
     * @return boolean
     */
    public function authenticate($login, $password, $remember, $hash = true)
    {
        if ($hash === true) {
            $password = $this->getHash($password);
        }

        $user = $this->userMapper->fetchByCredentials($login, $password);

        // If it's not empty. then login and password are both value
        if (!empty($user)) {
            
            $this->authManager->storeId($user['id'])
                              ->storeRole($user['role'])
                              ->login($login, $password, $remember);
            return true;
        }

        return false;
    }

    /**
     * Log-outs a user
     * 
     * @return void
     */
    public function logout()
    {
        return $this->authManager->logout();
    }

    /**
     * Checks whether a user is logged in
     * 
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->authManager->isLoggedIn();
    }

    /**
     * Provides a hash of a string
     * 
     * @param string $string
     * @return string
     */
    private function getHash($string)
    {
        return sha1($string);
    }

    /**
     * Adds a user
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input)
    {
        $input['password_hash'] = $this->getHash($input['password']);
        return $this->userMapper->insert(ArrayUtils::arrayWithout($input, array('password', 'password_confirm')));
    }

    /**
     * Updates a user
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        if (!empty($input['password'])) {
            $input['password_hash'] = $this->getHash($input['password']);
        }

        return $this->userMapper->update(ArrayUtils::arrayWithout($input, array('password', 'password_confirm')));
    }

    /**
     * Remove all but provided
     * 
     * @param int $id User's id to be kept
     * @return boolean
     */
    public function wipe($id)
    {
        return $this->userMapper->wipe($id);
    }

    /**
     * Deletes a user by associated id
     * 
     * @param string $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->userMapper->deleteById($id);
    }

    /**
     * Fetches user's entity by associated id
     * 
     * @param string $id User's id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->userMapper->fetchById($id));
    }

    /**
     * Fetches all entities
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->prepareResults($this->userMapper->fetchAll());
    }
}
