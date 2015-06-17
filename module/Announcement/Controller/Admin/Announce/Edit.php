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

final class Edit extends AbstractAnnounce
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id
	 * @return string
	 */
	public function indexAction($id)
	{
		$announce = $this->getAnnounceManager()->fetchById($id);

		if ($announce !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				
				'editing' => true,
				'title' => 'Edit the announce',
				'announce'	 => $announce
			)));

		} else {
			return false;
		}
	}

	/**
	 * Updates a category
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {
			$announceManager = $this->getAnnounceManager();;

			if ($announceManager->update($this->request->getPost())) {

				$this->flashMessenger->set('success', 'The announce has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
