<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Controller;

use Krystal\Stdlib\VirtualEntity;
use Krystal\Validate\Pattern;

final class Page extends AbstractPagesController
{
	/**
	 * Renders a page by its associated id
	 * 
	 * @param string $id Page's id
	 * @return string
	 */
	public function indexAction($id)
	{
		// If id is null, then a default page must be fetched
		if (is_null($id)) {
			$page = $this->getPageManager()->fetchDefault();
		} else {
			$page = $this->getPageManager()->fetchById($id);
		}

		// If $page isn't false, then the right $id is supplied
		if ($page !== false) {

			$this->loadSitePlugins();
			$this->loadBreadcrumbsByPageEntity($page);

			return $this->view->render($this->grabTemplateName($page), array(
				'page' => $page
			));

		} else {

			// Returning false from controller's action triggers 404 error automatically
			return false;
		}
	}

	/**
	 * Displays "404: Not found" page
	 * 
	 * @return string
	 */
	public function notFoundAction()
	{
		$this->loadSitePlugins();
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => '404',
				'link' => '#'
			)
		));

		$page = new VirtualEntity();
		$page->setTitle($this->translator->translate('Page not found'))
			 ->setContent($this->translator->translate('Requested page doesn\'t exist'))
			 ->setSeo(false);

		// There's no need to set 404 status code here, as its handled by the router internally
		return $this->view->render('pages-404', array(
			'page' => $page
		));
	}

	/**
	 * Displays a home page
	 * 
	 * @return string
	 */
	public function homeAction()
	{
		// The reason it's implemented this way, is because the CMS itself can be used to manage a single landing page
		// Landings pages mostly contain contact form at the bottom, so the request is handled within the same action
		if ($this->request->isPost()) {
			return $this->submitAction();
		} else {
			return $this->homePageAction();
		}
	}

	/**
	 * Displays home page
	 * 
	 * @return string
	 */
	private function homePageAction()
	{
		$pageManager = $this->getPageManager();
		$page = $pageManager->fetchDefault();

		if ($page !== false) {
			$this->loadSitePlugins();
			// Clear all breadcrumbs
			$this->view->getBreadcrumbBag()->clear();

			return $this->view->render('pages-home', array(
				'page' => $page
			));

		} else {

			// Returning false from a controller's action triggers 404 error automatically
			return false;
		}
	}

	/**
	 * Processes a POST request that comes from home page
	 * 
	 * @return string
	 */
	private function submitAction()
	{
		// Grab all POST data
		$input = $this->request->getPost();
		$formValidator = $this->getValidator($input);

		if ($formValidator->isValid()) {

			// Send a message to site owner. Assuming the following responses will be caught by AJAX handler
			return $this->sendMessage($input) ? '1' : '0';

		} else {
			return $formValidator->getErrors();
		}
	}

	/**
	 * Returns prepared form validator
	 * 
	 * @param array $input
	 * @return \Krystal\Validate\ValidatorChain
	 */
	private function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					// Common rules for common contact forms
					'name' => new Pattern\Name(),
					'email' => new Pattern\Email(),
					'message' => new Pattern\Message()
				)
			)
		));
	}

	/**
	 * Sends a message from the input
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	private function sendMessage(array $input)
	{
		// Render the body firstly
		$message = $this->view->renderRaw($this->moduleName, 'messages', 'message', array(
			'input' => $input
		));

		// Prepare a subject
		$subject = $this->translator->translate('You have received a new message from %s', $input['name']);

		// Grab mailer service
		$mailer = $this->getService('Cms', 'mailer');
		return $mailer->send($subject, $message, 'You have received a new message');
	}

	/**
	 * Grabs template name
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $page
	 * @return string
	 */
	private function grabTemplateName(VirtualEntity $page)
	{
		if (trim($page->getTemplate()) !== '') {
			return $page->getTemplate();
		} else {
			return 'pages-page';
		}
	}

	/**
	 * Loads breadcrumb by page's entity
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $page
	 * @return void
	 */
	private function loadBreadcrumbsByPageEntity(VirtualEntity $page)
	{
		$breadcrumbBag = $this->view->getBreadcrumbBag();

		// If page isn't default, then we append a breadcrumb
		if (!$page->getDefault()) {
			$breadcrumbBag->add($this->getPageManager()->getBreadcrumbs($page));
		} else {
			// Otherwise we should never have breadcrumbs
			$breadcrumbBag->clear();
		}
	}
}
