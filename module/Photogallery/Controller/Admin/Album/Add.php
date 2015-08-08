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

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractAlbum
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		$album = new VirtualEntity();
		$album->setSeo(true);

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'title' => 'Add an album',
			'album' => $album
		)));
	}

	/**
	 * Add an album
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('album'));

		if ($formValidator->isValid()) {

			$albumManager = $this->getAlbumManager();
			$albumManager->add($this->request->getPost());

			$this->flashBag->set('success', 'An album has been created successfully');

			return $albumManager->getLastId();

		} else {

			return $formValidator->getErrors();
		}
	}
}
