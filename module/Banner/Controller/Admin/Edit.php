<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Banner\Controller\Admin;

final class Edit extends AbstractBanner
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id Banner's id
	 * @return string
	 */
	public function indexAction($id)
	{
		$banner = $this->getBannerManager()->fetchById($id);

		if ($banner !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'editing' => true,
				'title' => 'Edit the banner',
				'banner' => $banner
			)));
			
		} else {

			return false;
		}
	}

	/**
	 * Updates a banner
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost(), $this->request->getFiles(), true);

		if ($formValidator->isValid()) {

			if ($this->getBannerManager()->update($this->request->getAll())) {

				$this->flashMessenger->set('success', 'A banner has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
