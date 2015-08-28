<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa\Controller;

use Site\Controller\AbstractController;
use Krystal\Validate\Pattern;

final class Qa extends AbstractController
{
	/**
	 * Lists all pairs with pagination
	 * 
	 * @param string $id Page id
	 * @param string $pageNumber Page id
	 * @param string $code Language code
	 * @param string $slug Page slug
	 * @return string
	 */
	public function indexAction($id = false, $pageNumber = 1, $code = null, $slug = null)
	{
		if ($this->request->isPost()) {
			return $this->submitAction();
		} else {
			return $this->showAction($id, $pageNumber, $code, $slug);
		}
	}

	/**
	 * Displays a front page
	 * 
	 * @param string $id Page id
	 * @param string $pageNumber Page id
	 * @param string $code Language code
	 * @param string $slug Page slug
	 * @return string
	 */
	private function showAction($id, $pageNumber = 1, $code = null, $slug = null)
	{
		$pageManager = $this->getService('Pages', 'pageManager');
		$page = $pageManager->fetchById($id);

		if ($page !== false) {

			// Load all view plugins
			$this->loadPlugins($pageManager->getBreadcrumbs($page));

			$qaManager = $this->getModuleService('qaManager');
			$pairs = $qaManager->fetchAllPublishedByPage($pageNumber, $this->getConfig()->getPerPageCount());

			// Tweak pagination service
			$paginator = $qaManager->getPaginator();
			$this->preparePaginator($paginator, $code, $slug, $pageNumber);

			return $this->view->render('qa', array(
				'pairs' => $pairs,
				'paginator' => $paginator,
				'page' => $page
			));

		} else {

			return false;
		}
	}

	/**
	 * Handles form submission
	 * 
	 * @return string
	 */
	private function submitAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$data = array_merge($this->request->getPost(), array('ip' => $this->request->getClientIp()));
			$qaManager = $this->getModuleService('qaManager');

			if ($qaManager->send($data)) {

				$this->flashBag->set('success', 'Your question has been sent! Thank you!');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}

	/**
	 * Loads site plugins
	 * 
	 * @param array $breadcrumbs
	 * @return void
	 */
	private function loadPlugins($breadcrumbs)
	{
		// Load asset plugins and tweak breadcrumbs
		$this->loadSitePlugins();
		$this->view->getBreadcrumbBag()->add($breadcrumbs);
	}

	/**
	 * Returns configuration entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	private function getConfig()
	{
		return $this->getModuleService('configManager')->getEntity();
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
					'questioner' => new Pattern\Name(),
					'question' => new Pattern\Message(),
					'captcha' => new Pattern\Captcha($this->captcha)
				)
			)
		));
	}
}
