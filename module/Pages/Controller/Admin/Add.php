<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Controller\Admin;

final class Add extends AbstractPage
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
			'title' => 'Add a page',
			'page' => $this->getPageManager()->fetchDummy()
		)));
	}

	/**
	 * Adds a page
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {
			$pageManager = $this->getPageManager();

			if ($pageManager->add($this->request->getPost())) {

				$this->flashMessenger->set('success', 'A page has been created successfully');
				return $pageManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
