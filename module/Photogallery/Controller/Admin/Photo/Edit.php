<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Controller\Admin\Photo;

final class Edit extends AbstractPhoto
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id
	 * @return string
	 */
	public function indexAction($id)
	{
		$photo = $this->getPhotoManager()->fetchById($id);

		if ($photo !== false) {

			$this->view->getPluginBag()->load('zoom');
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'title' => 'Edit the photo',
				'photo' => $photo,
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates a photo
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('photo'), $this->request->getFiles(), true);

		if ($formValidator->isValid()) {

			$this->flashBag->set('success', 'The photo has been updated successfully');
			return $this->getPhotoManager()->update($this->request->getAll()) ? '1' : '0';

		} else {

			return $formValidator->getErrors();
		}
	}
}
