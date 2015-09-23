<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Controller\Admin;

final class Edit extends AbstractAdvice
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id Advice id
	 * @return string
	 */
	public function indexAction($id)
	{
		$advice = $this->getAdviceManager()->fetchById($id);

		if ($advice !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'title' => 'Edit the advice',
				'advice' => $advice
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates an advice
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('advice'));

		if ($formValidator->isValid()) {

			if ($this->getAdviceManager()->update($this->request->getPost('advice'))) {

				$this->flashBag->set('success', 'The advice has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
