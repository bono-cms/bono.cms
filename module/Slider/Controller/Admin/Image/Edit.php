<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Controller\Admin\Image;

final class Edit extends AbstractImage
{
	/**
	 * Shows an editing form
	 * 
	 * @param string $id
	 * @return string
	 */
	public function indexAction($id)
	{
		$image = $this->getImageManager()->fetchById($id);

		if ($image !== false) {
			$this->loadSharedPlugins(false);

			return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
				'title' => 'Edit the slider',
				'image' => $image
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates an image
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('image'), $this->request->getFiles(), true);

		if ($formValidator->isValid()) {

			if ($this->getImageManager()->update($this->request->getAll())) {

				$this->flashBag->set('success', 'The slider has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
