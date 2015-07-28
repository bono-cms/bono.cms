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

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractPhoto
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->view->getPluginBag()
				   ->load('preview');

		$this->loadSharedPlugins();

		$photo = new VirtualEntity();
		$photo->setPublished(true)
			  ->setOrder(0);

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'title' => 'Add a photo',
			'photo' => $photo
		)));
	}

	/**
	 * Adds a photo
	 * 
	 * @return string The response
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost(), $this->request->getFiles());

		if ($formValidator->isValid()) {

			$photoManager = $this->getPhotoManager();
			$photoManager->add($this->request->getAll());

			$this->flashBag->set('success', 'A photo has been added successfully');

			return $photoManager->getLastId();

		} else {

			return $formValidator->getErrors();
		}
	}
}
