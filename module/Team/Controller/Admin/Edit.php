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

final class Edit extends AbstractMember
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id
	 * @return string
	 */
	public function indexAction($id)
	{
		$member = $this->getTeamManager()->fetchById($id);

		if ($member !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'editing' => true,
				'title' => 'Edit the member',
				'member' => $member
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates a team member
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost(), $this->request->getFiles(), true);

		if ($formValidator->isValid()) {

			if ($this->getTeamManager()->update($this->request->getAll())) {

				$this->flashMessenger->set('success', 'The member has been updated successfully');
				return '1';
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
