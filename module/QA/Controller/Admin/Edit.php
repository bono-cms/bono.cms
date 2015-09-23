<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa\Controller\Admin;

final class Edit extends AbstractQa
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id
	 * @return string
	 */
	public function indexAction($id)
	{
		$qa = $this->getQaManager()->fetchById($id);

		if ($qa !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
				'title' => 'Edit the pair',
				'qa' => $qa
			)));

		} else {
			return false;
		}
	}

	/**
	 * Updates a QA
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('qa'));

		if ($formValidator->isValid()) {

			if ($this->getQaManager()->update($this->request->getPost('qa'))) {

				$this->flashBag->set('success', 'The pair has been updated successfully');
				return '1';
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
