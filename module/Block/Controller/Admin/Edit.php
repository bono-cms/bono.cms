<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Controller\Admin;

final class Edit extends AbstractBlock
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id Block id
	 * @return string
	 */
	public function indexAction($id)
	{
		$block = $this->getBlockManager()->fetchById($id);

		if ($block !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'editing' => true,
				'title' => 'Edit the block',
				'block' => $block
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates a block
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			if ($this->getBlockManager()->update($this->request->getPost())) {

				$this->flashMessenger->set('success', 'A block has been updated successfully');
				return '1';
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
