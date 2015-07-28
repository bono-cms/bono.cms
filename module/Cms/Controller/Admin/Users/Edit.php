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

final class Edit extends AbstractUser
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id User's id
	 * @return string
	 */ 
	public function indexAction($id)
	{
		$user = $this->getUserManager()->fetchById($id);

		if ($user !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'editing' => true,
				'title' => 'Edit the user',
				'user' => $user
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates an user
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost(), true);

		if ($formValidator->isValid()) {

			if ($this->getUserManager()->update($this->request->getPost())) {

				$this->flashBag->set('success', 'The user has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
