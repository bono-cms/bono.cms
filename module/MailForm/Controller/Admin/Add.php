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

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractForm
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		$form = new VirtualEntity();
		$form->setSeo(true)
			 ->setMessageView('message');

		return $this->view->render('form', $this->getSharedVars(array(
			'title' => 'Add a form',
			'form' => $form
		)));
	}

	/**
	 * Adds a form
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('form'));

		if ($formValidator->isValid()){
			$formManager = $this->getFormManager();

			if ($formManager->add($this->request->getPost())) {

				$this->flashBag->set('success', 'A form has been added successfully');
				return $formManager->getLastId();
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
