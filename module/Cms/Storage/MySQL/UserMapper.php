<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
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
		return 'bono_module_cms_users';
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
						->from(static::getTableName())
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
						->from(static::getTableName())
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
