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

final class Add extends AbstractQa
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
			'title' => 'Add a pair',
			'qa' => $this->getQaManager()->fetchDummy()
		)));
	}

	/**
	 * Adds a QA
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {
			$qaManager = $this->getQaManager();

			if ($qaManager->add($this->request->getPost())) {

				$this->flashMessenger->set('success', 'A pair has been added successfully');
				return $qaManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
