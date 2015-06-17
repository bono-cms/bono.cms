<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Controller\Admin;

final class Add extends AbstractFaq
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
			'title' => 'Add new FAQ',
			'faq' => $this->getFaqManager()->fetchDummy()
		)));
	}

	/**
	 * Adds a faq
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$faqManager = $this->getFaqManager();
			
			if ($faqManager->add($this->request->getPost())) {
				$this->flashMessenger->set('success', 'A faq has been created successfully');
				return $faqManager->getLastId();
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
