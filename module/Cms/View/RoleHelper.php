<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\View;

use Krystal\Authentication\RoleHelper as Facade;

final class RoleHelper extends Facade
{
	private $id;

	/**
	 * @return void
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return boolean
	 */
	public function isUser()
	{
		return $this->is('user');
	}

	/**
	 * Whether current role is guest
	 * 
	 * @return boolean
	 */
	public function isGuest()
	{
		return $this->is('guest');
	}

	/**
	 * Whether current role is developer
	 * 
	 * @return boolean
	 */
	public function isDeveloper()
	{
		return $this->is('dev');
	}
}
