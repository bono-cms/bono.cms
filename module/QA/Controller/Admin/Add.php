<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa\Controller\Admin;

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractQa
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		$qa = new VirtualEntity();
		$qa->setTimestampAsked(time())
		   ->setTimestampAnswered(time())
		   ->setPublished(true);

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'title' => 'Add a pair',
			'qa' => $qa
		)));
	}

	/**
	 * Adds a QA
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('qa'));

		if ($formValidator->isValid()) {

			$qaManager = $this->getQaManager();
			$data = array_merge($this->request->getPost('qa'), array('ip' => $this->request->getClientIp()));

			if ($qaManager->add($data)) {

				$this->flashBag->set('success', 'A pair has been added successfully');
				return $qaManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
