<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Controller\Admin;

use Krystal\Stdlib\VirtualEntity;

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

		$page = new VirtualEntity();
		$page->setSeo(true)
			 ->setController('Pages:Page@indexAction');

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'title' => 'Add a page',
			'page' => $page
		)));
	}

	/**
	 * Adds a page
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('page'));

		if ($formValidator->isValid()) {
			$pageManager = $this->getPageManager();

			if ($pageManager->add($this->request->getPost())) {

				$this->flashBag->set('success', 'A page has been created successfully');
				return $pageManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
