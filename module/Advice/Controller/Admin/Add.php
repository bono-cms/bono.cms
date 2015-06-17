<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Controller\Admin;

final class Add extends AbstractAdvice
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
			'title' => 'Add an advice',
			'advice' => $this->getAdviceManager()->fetchDummy()
		)));
	}

	/**
	 * Adds an advice
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$adviceManager = $this->getAdviceManager();

			if ($adviceManager->add($this->request->getPost())) {

				$this->flashMessenger->set('success', 'An advice has been created successfully');
				return $adviceManager->getLastId();
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
