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

final class Edit extends AbstractPage
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id Page's id
	 * @return string
	 */
	public function indexAction($id)
	{
		$page = $this->getPageManager()->fetchById($id);

		if ($page !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
				'page' => $page,
				'title' => 'Edit the page',
			)));

		} else {
			return false;
		}
	}

	/**
	 * Updates a page
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('page'));

		if ($formValidator->isValid()) {
			if ($this->getPageManager()->update($this->request->getPost())) {

				$this->flashBag->set('success', 'The page has been updated successfully');
				return '1';
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
