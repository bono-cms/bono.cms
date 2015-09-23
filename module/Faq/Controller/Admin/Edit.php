<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Controller\Admin;

final class Edit extends AbstractFaq
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id
	 * @return string
	 */
	public function indexAction($id)
	{
		$faq = $this->getFaqManager()->fetchById($id);

		if ($faq !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
				'title' => 'Edit the FAQ',
				'faq' => $faq
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates a FAQ
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('faq'));

		if ($formValidator->isValid()) {

			if ($this->getFaqManager()->update($this->request->getPost('faq'))) {

				$this->flashBag->set('success', 'The FAQ has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
