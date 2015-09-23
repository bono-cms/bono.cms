<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
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

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
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
		$formValidator = $this->getValidator($this->request->getPost('announce'));

		if ($formValidator->isValid()) {

			$announceManager = $this->getAnnounceManager();

			if ($announceManager->add($this->request->getPost('announce'))) {

				$this->flashBag->set('success', 'An announce has been created successfully');
				return $announceManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
