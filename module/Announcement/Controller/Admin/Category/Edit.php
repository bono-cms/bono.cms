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

final class Edit extends AbstractCategory
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id
	 * @return void
	 */
	public function indexAction($id)
	{
		$category = $this->getCategoryManager()->fetchById($id);

		if ($category !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'title' => 'Edit the category',
				'category' => $category
			)));

		} else {

			return false;
		}
	}

	/**
	 * Save the changes
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

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
