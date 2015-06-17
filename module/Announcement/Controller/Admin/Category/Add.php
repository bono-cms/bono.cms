<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Controller\Admin\Category;

final class Add extends AbstractCategory
{
	/**
	 * Shows add form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(

			'title' => 'Add a category',
			'category' => $this->getCategoryManager()->fetchDummy()
		)));
	}

	/**
	 * Adds a category
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {
			$categoryManager = $this->getCategoryManager();

			if ($categoryManager->add($this->request->getPost())) {

				$this->flashMessenger->set('success', 'A category has been created successfully');
				return $categoryManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
