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

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractBanner
{
	/**
	 * Shows add form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'title' => 'Add a banner',
			'banner' => new VirtualEntity()
		)));
	}

	/**
	 * Adds a banner
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('banner'), $this->request->getFiles());

		if ($formValidator->isValid()) {

			$bannerManager = $this->getBannerManager();

			if ($bannerManager->add($this->request->getAll())) {

				$this->flashBag->set('success', 'A banner has been added successfully');
				return $bannerManager->getLastId();
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
