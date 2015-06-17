<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Controller;

use Site\Controller\AbstractController;
use Krystal\Validate\Pattern;

final class Form extends AbstractController
{
	/**
	 * Shows a form
	 * 
	 * @param string $id Form id
	 * @return string
	 */
	public function indexAction($id)
	{
		if ($this->request->isPost()) {
			return $this->sendAction();

		} else {
			return $this->showAction($id);
		}
	}

	/**
	 * Shows a form
	 * 
	 * @param string $id Form's id
	 * @return string
	 */
	private function showAction($id)
	{
		$form = $this->getFormManager()->fetchById($id);

		if ($form !== false) {
			$this->loadPlugins($form);

			return $this->view->render($form->getTemplate(), array(
				'page' => $form
			));

		} else {

			// Returning false will trigger 404 error automatically
			return false;
		}
	}

	/**
	 * Loads all required plugins for the template
	 * 
	 * @param $form
	 * @return void
	 */
	private function loadPlugins($form)
	{
		$this->loadSitePlugins();

		// Append handler for a site
		$this->view->getPluginBag()->appendScript($this->getWithAssetPath('/form.js'));
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => $form->getTitle()
			)
		));
	}

	/**
	 * Returns prepared form validator
	 * 
	 * @param array $input Raw input data
	 * @return \Krystal\Validate\ValidatorChain
	 */
	private function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					'name' => new Pattern\Name(),
					'email' => new Pattern\Email(),
					'message' => new Pattern\Message(),
					'captcha' => new Pattern\Captcha($this->captcha)
				)
			)
		));
	}

	/**
	 * Returns form manager
	 * 
	 * @return \MailForm\Service\FormManager
	 */
	private function getFormManager()
	{
		return $this->moduleManager->getModule('MailForm')->getService('formManager');
	}

	/**
	 * Sends a form
	 * 
	 * @return string
	 */
	public function sendAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			// Here need to send a message
			if ($this->getFormManager()->send($this->request->getPost())) {

				$flashKey = 'success';
				$flashMessage = 'Your message has been sent';
				
			} else {
				
				$flashKey = 'warning';
				$flashMessage = 'Could not send your message. Please again try later';
			}

			$this->flashMessenger->set($flashKey, $flashMessage);

			return '1';

		} else {

			return $formValidator->getErrors();
		}
	}
}
