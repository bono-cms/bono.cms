<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\View;

use Krystal\Authentication\RoleHelper as Facade;

final class RoleHelper extends Facade
{
	/**
	 * Target user id
	 * 
	 * @var string
	 */
	private $id;

	/**
	 * Defines user id
	 * 
	 * @param string $id
	 * @return void
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Returns user id
	 * 
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Determines whether current role is user
	 * 
	 * @return boolean
	 */
	public function isUser()
	{
		return $this->is('user');
	}

	/**
	 * Determines whether current role is guest
	 * 
	 * @return boolean
	 */
	public function isGuest()
	{
		return $this->is('guest');
	}

	/**
	 * Determines whether current role is developer
	 * 
	 * @return boolean
	 */
	public function isDeveloper()
	{
		return $this->is('dev');
	}
}
