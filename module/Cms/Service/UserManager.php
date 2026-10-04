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
use Krystal\Stdlib\ArrayUtils;

final class UserManager extends AbstractManager implements UserManagerInterface
{
    /**
     * Any compliant user mapper
     * 
     * @var \Cms\Storage\UserMapperInterface
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
     * @param \Cms\Storage\UserMapperInterface $userMapper
     * @param \Krystal\Authentication\AuthManagerInterface $authManager
     * @return void
     */
    public function __construct(UserMapperInterface $userMapper, AuthManagerInterface $authManager)
    {
        $this->userMapper = $userMapper;
        $this->authManager = $authManager;

        // Configure the auth manager to use this class as user provider
        $this->authManager->setUserProvider([$this, 'provideUser']);
    }

    /**
     * User provider used by AuthManager
     * 
     * Accepts either login (string) or user id
     * 
     * @param string|int $identifier
     * @return array|null
     */
    public function provideUser($identifier)
    {
        // First try by ID
        $user = $this->userMapper->fetchById($identifier);

        // Fallback to login
        if (empty($user)) {
            $user = $this->userMapper->fetchByLogin($identifier);
        }

        if (empty($user)) {
            return null;
        }

        // Ensure required keys exist
        if (!isset($user['remember_token_version'])) {
            $user['remember_token_version'] = 1;
        }

        return $user;
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
     * @return string
     */
    public function fetchNameById($id)
    {
        static $cache = [];

        if (isset($cache[$id])) {
            return $cache[$id];
        }

        $name = $this->userMapper->fetchNameById($id);
        $cache[$id] = $name;

        return $name;
    }

    /**
     * Returns last added user's id
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

        if (isset($user['remember_token_version'])) {
            $entity->setRememberTokenVersion($user['remember_token_version'], VirtualEntity::FILTER_INT);
        }

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
     * @param string $password Plain password
     * @param boolean $remember
     * @return boolean
     */
    public function authenticate($login, $password, $remember)
    {
        return $this->authManager->login($login, $password, $remember);
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
     * Creates a secure password hash
     * 
     * @param string $password
     * @return string
     */
    private function createHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Adds a user
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input)
    {
        $input['password_hash'] = $this->createHash($input['password']);
        $input['remember_token_version'] = 1;

        return $this->userMapper->insert(ArrayUtils::arrayWithout($input, ['password', 'password_confirm']));
    }

    /**
     * Updates a user
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        // Only update password if a new one was provided
        if (!empty($input['password'])) {
            $input['password_hash'] = $this->createHash($input['password']);

            // Invalidate all existing remember-me cookies
            if (isset($input['id'])) {
                $current = $this->userMapper->fetchById($input['id']);
                $version = isset($current['remember_token_version']) ? (int) $current['remember_token_version'] : 1;
                $input['remember_token_version'] = $version + 1;
            }
        }

        return $this->userMapper->update(ArrayUtils::arrayWithout($input, ['password', 'password_confirm']));
    }

    /**
     * Removes all users except the provided one
     * 
     * @param integer $id
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
     * @param string $id
     * @return \Krystal\Stdlib\VirtualEntity|boolean
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
