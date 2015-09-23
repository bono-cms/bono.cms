<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Controller\Admin\Category;

final class Edit extends AbstractCategory
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id
	 * @return string
	 */
	public function indexAction($id)
	{
		$category = $this->getCategoryManager()->fetchById($id);

		if ($category !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
				'editing' => true,
				'title' => 'Edit the category',
				'category' => $category
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
		$formValidator = $this->getValidator($this->request->getPost('category'));

		if ($formValidator->isValid()) {

			if ($this->getCategoryManager()->update($this->request->getPost())) {

				$this->flashBag->set('success', 'Category has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
