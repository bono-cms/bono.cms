<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Controller\Admin;

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractMember
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		$member = new VirtualEntity();
		$member->setPublished(true);

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'title' => 'Add a member',
			'member' => $member,
		)));
	}

	/**
	 * Adds a team member
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('team'), $this->request->getFiles());

		if ($formValidator->isValid()) {

			$teamManager = $this->getTeamManager();

			if ($teamManager->add($this->request->getAll())) {

				$this->flashBag->set('success', 'A member has been added successfully');
				return $teamManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
