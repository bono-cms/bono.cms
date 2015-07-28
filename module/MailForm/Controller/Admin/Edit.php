<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Controller\Admin;

final class Edit extends AbstractForm
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id Form id
	 * @return string
	 */
	public function indexAction($id)
	{
		$form = $this->getFormManager()->fetchById($id);

		// When $form isn't false, then its a form bag's instance
		if ($form !== false) {
			$this->loadSharedPlugins();

			return $this->view->render('form', $this->getSharedVars(array(
				'title' => 'Edit the form',
				'form' => $form
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates a form
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			if ($this->getFormManager()->update($this->request->getPost())) {
				$this->flashBag->set('success', 'The form has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
