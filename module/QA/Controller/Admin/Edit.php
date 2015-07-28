<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace QA\Controller\Admin;

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

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
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
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			if ($this->getQaManager()->update($this->request->getPost())) {

				$this->flashBag->set('success', 'The pair has been updated successfully');
				return '1';
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
