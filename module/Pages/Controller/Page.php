<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Controller;

use Krystal\Stdlib\VirtualEntity;

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
		$formValidator = $this->getValidator();

		if ($formValidator->isValid()) {
			// Handle submission here
		} else {
			return $formValidator->getErrors();
		}
	}

	/**
	 * Returns prepared form validator
	 * 
	 * @return \Krystal\Validate\ValidatorChain
	 */
	private function getValidator()
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $this->request->getPost(),
				'definition' => array(
					// Validation rules must be defined here
				)
			)
		));
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
