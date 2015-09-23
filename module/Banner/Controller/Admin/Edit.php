<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
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

			return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
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
		$formValidator = $this->getValidator($this->request->getPost('banner'), $this->request->getFiles(), true);

		if ($formValidator->isValid()) {

			if ($this->getBannerManager()->update($this->request->getAll())) {

				$this->flashBag->set('success', 'A banner has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
