<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Controller\Admin\Album;

final class Edit extends AbstractAlbum
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id Album id
	 * @return string
	 */
	public function indexAction($id)
	{
		$album = $this->getAlbumManager()->fetchById($id);

		if ($album !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'title' => 'Edit the album',
				'album' => $album,
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates an album
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('album'));

		if ($formValidator->isValid()) {

			$albumManager = $this->getAlbumManager();
			$albumManager->update($this->request->getPost());

			$this->flashBag->set('success', 'The album has been updated successfully');

			return '1';

		} else {

			return $formValidator->getErrors();
		}
	}
}
