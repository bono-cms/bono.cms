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

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractCategory
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();
		$this->view->getPluginBag()->load('preview');
		
		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'title' => 'Add a category',
			'category' => new VirtualEntity()
		)));
	}

	/**
	 * Adds a category
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('category'), $this->request->getFiles());

		if ($formValidator->isValid()) {

			$categoryManager = $this->getCategoryManager();

			if ($categoryManager->add($this->request->getAll())) {

				$this->flashBag->set('success', 'A category has been added successfully');
				return $categoryManager->getLastId();
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
