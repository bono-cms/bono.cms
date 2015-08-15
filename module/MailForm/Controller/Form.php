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
use Krystal\Stdlib\VirtualEntity;
use RuntimeException;

final class Form extends AbstractController
{
	/**
	 * Returns a list of validation rules for dynamic forms
	 * 
	 * @param array $input Raw post data
	 * @return array
	 */
	private function getValidationRules(array $input)
	{
		return array(
			'1' => array(
				'input' => array(
					'source' => $input,
					'definition' => array(
						'name' => new Pattern\Name(),
						'email' => new Pattern\Email(),
						'message' => new Pattern\Message(),
						'captcha' => new Pattern\Captcha($this->captcha)
					)
				)
			)
		);
	}

	/**
	 * Shows a form
	 * 
	 * @param string $id Form id
	 * @return string
	 */
	public function indexAction($id)
	{
		if ($this->request->isPost()) {
			return $this->sendAction($id);

		} else {
			return $this->showAction($id);
		}
	}

	/**
	 * Shows a form
	 * 
	 * @param string $id Form id
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
	 * Sends a form
	 *  
	 * @param string $id Form id
	 * @return string
	 */
	private function sendAction($id)
	{
		$formValidator = $this->getValidator($id, $this->request->getPost());

		if ($formValidator->isValid()) {

			// It's time to send a message
			if ($this->sendMessage($id, $this->request->getPost())) {
				$this->flashBag->set('success', 'Your message has been sent');
			} else {
				$this->flashBag->set('warning', 'Could not send your message. Please again try later');
			}

			return '1';

		} else {

			return $formValidator->getErrors();
		}
	}

	/**
	 * Prepares a view object to send messages
	 * 
	 * @return \Krystal\Application\View\ViewManager
	 */
	private function getMessageView()
	{
		// Special case, when override must be done
		$resolver = $this->view->getResolver();
		$resolver->setModule('MailForm')
				 ->setTheme('messages');

		$this->view->disableLayout();

		return $this->view;
	}

	/**
	 * Sends a message from the input
	 * 
	 * @param string $id Form id
	 * @param array $input
	 * @return boolean
	 */
	private function sendMessage($id, array $input)
	{
		$template = $this->getFormManager()->fetchMessageViewById($id);

		// Render the body firstly
		$body = $this->getMessageView()->render($template, array(
			'input' => $input
		));

		// Prepare a subject
		$subject = $this->translator->translate('You received a new message from %s <%s>', $input['name'], $input['email']);

		return $this->getFormManager()->send($subject, $body);
	}

	/**
	 * Loads all required plugins for the template
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $form
	 * @return void
	 */
	private function loadPlugins(VirtualEntity $form)
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
	 * @param string $id Form id
	 * @param array $input Raw input data
	 * @throws \RuntimeException if attempted to get non-attached validation rule
	 * @return \Krystal\Validate\ValidatorChain
	 */
	private function getValidator($id, array $input)
	{
		$rules = $this->getValidationRules($input);

		if (!isset($rules[$id])) {
			throw new RuntimeException(sprintf('No validation rules found for %s id', $id));
		} else {
			$options = $rules[$id];
		}

		return $this->validatorFactory->build($options);
	}

	/**
	 * Returns form manager
	 * 
	 * @return \MailForm\Service\FormManager
	 */
	private function getFormManager()
	{
		return $this->getModuleService('formManager');
	}
}
