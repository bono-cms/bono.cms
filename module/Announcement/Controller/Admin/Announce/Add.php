<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Controller\Admin\Announce;

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractAnnounce
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		$announce = new VirtualEntity();
		$announce->setSeo(true);
		$announce->setPublished(true);

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'title' => 'Add new announce',
			'announce' => $announce
		)));
	}

	/**
	 * Adds an announce
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$announceManager = $this->getAnnounceManager();

			if ($announceManager->add($this->request->getPost())) {

				$this->flashBag->set('success', 'An announce has been created successfully');
				return $announceManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
