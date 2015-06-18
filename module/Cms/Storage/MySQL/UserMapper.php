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
	protected $table = 'bono_module_cms_users';

	/**
	 * Inserts user's data
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->db->insert($this->table, array(

			'login'			=> $input['login'],
			'password_hash'	=> $input['password_hash'],
			'role'			=> $input['role'],
			'email'			=> $input['email'],
			'name'			=> $input['name'],

		))->execute();
	}

	/**
	 * Updates user's data
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->db->update($this->table, array(

			'login'			=> $input['login'],
			'password_hash'	=> $input['password_hash'],
			'role'			=> $input['role'],
			'email'			=> $input['email'],
			'name'			=> $input['name'],

		))->whereEquals('id', $input['id'])
		  ->execute();
	}

	/**
	 * Fetches user's name by associated id
	 * 
	 * @param string $id User id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->db->select('name')
						->from($this->table)
						->whereEquals('id', $id)
						->query('name');
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
						->from($this->table)
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
						->from($this->table)
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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('id', $id)
						->query();
	}

	/**
	 * Deletes a user by associated id
	 * 
	 * @param string $id User's id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('id', $id)
						->execute();
	}
}
