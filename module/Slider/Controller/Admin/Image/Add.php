<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Controller\Admin\Image;

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractImage
{
	/**
	 * Shows the adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->view->getPluginBag()->load('preview')
								   ->appendScript($this->getWithAssetPath('/admin/image.add.js'));

		$image = new VirtualEntity();
		$image->setPublished(true)
			  ->setOrder(0);

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'title' => 'Add a slider',
			'image' => $image
		)));
	}

	/**
	 * Adds a slider
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('image'), $this->request->getFiles());

		if ($formValidator->isValid()) {

			$imageManager = $this->getImageManager();

			if ($imageManager->add($this->request->getAll())) {

				$this->flashBag->set('success', 'A slider has been added successfully');
				return $imageManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
