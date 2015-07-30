<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin\Users;

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractUser
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'user' => new VirtualEntity(),
			'title' => 'Add a user'
		)));
	}

	/**
	 * Adds a user
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('user'));

		if ($formValidator->isValid()) {

			$userManager = $this->getUserManager();
			$userManager->add($this->request->getPost('user'));

			$this->flashBag->set('success', 'A user has been created successfully');

			return $userManager->getLastId();

		} else {

			return $formValidator->getErrors();
		}
	}
}
