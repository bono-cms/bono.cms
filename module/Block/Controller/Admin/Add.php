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

final class Add extends AbstractBlock
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'block' => $this->getBlockManager()->fetchDummy(),
			'title' => 'Add a block'
		)));
	}

	/**
	 * Adds a block
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$blockManager = $this->getBlockManager();

			if ($blockManager->add($this->request->getPost())) {

				$this->flashMessenger->set('success', 'A block has been created successfully');
				return $blockManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
