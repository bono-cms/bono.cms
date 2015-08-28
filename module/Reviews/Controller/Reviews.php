<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Controller;

use Site\Controller\AbstractController;
use Krystal\Validate\Pattern;

final class Reviews extends AbstractController
{
	/**
	 * Shows reviews
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
	 * Shows a web-page with reviews
	 * 
	 * @param string $id Page id
	 * @param string $pageNumber Page id
	 * @param string $code Language code
	 * @param string $slug Page slug
	 * @return string
	 */
	private function showAction($id, $pageNumber, $code, $slug)
	{
		$pageManager = $this->getService('Pages', 'pageManager');
		$page = $pageManager->fetchById($id);

		if ($page !== false) {

			// Prepare view
			$this->loadSitePlugins();
			$this->view->getBreadcrumbBag()->add($pageManager->getBreadcrumbs($page));

			$reviewManager = $this->getReviewsManager();
			$reviews = $reviewManager->fetchAllPublishedByPage($pageNumber, $this->getConfig()->getPerPageCount());

			$paginator = $reviewManager->getPaginator();
			$this->preparePaginator($paginator, $code, $slug, $pageNumber);

			return $this->view->render('reviews', array(
				'reviews' => $reviews,
				'paginator' => $paginator,
				'page' => $page
			));

		} else {
			return false;
		}
	}

	/**
	 * Adds a review by the user
	 * 
	 * @return string The response
	 */
	public function submitAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {
			$data = array_merge($this->request->getPost(), array('ip' => $this->request->getClientIP()));

			if ($this->getReviewsManager()->send($data, $this->getConfig()->getEnabledModeration())) {
				$this->flashBag->set('success', 'Your review has been sent! Thank you');
			}

			return '1';

		} else {

			return $formValidator->getErrors();
		}
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
	 * Returns reviews manager
	 * 
	 * @return \Reviews\Service\ReviewsManager
	 */
	private function getReviewsManager()
	{
		return $this->getModuleService('reviewsManager');
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
					'captcha' => new Pattern\Captcha($this->captcha),
					'review' => new Pattern\Message()
				)
			)
		));
	}
}
