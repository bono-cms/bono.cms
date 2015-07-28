<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller\Admin\Category;

final class Edit extends AbstractCategory
{
	/**
	 * Shows category edit form
	 * 
	 * @param string $id Category id
	 * @return string
	 */
	public function indexAction($id)
	{
		$category = $this->getCategoryManager()->fetchById($id);

		if ($category !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'title' => 'Edit the category',
				'category' => $category,
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates a category
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('category'), $this->request->getFiles());

		if ($formValidator->isValid()) {

			if ($this->getCategoryManager()->update($this->request->getAll())) {

				$this->flashBag->set('success', 'The category has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
