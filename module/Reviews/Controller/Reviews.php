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
	 * Shows a web-page with reviews
	 * 
	 * @param integer $page
	 * @return string
	 */
	public function showAction($page = 1)
	{
		$config = $this->getConfig();
		$reviewManager = $this->getReviewsManager();

		$paginator = $reviewManager->getPaginator();
		$this->preparePaginator($paginator);

		return $this->view->render('reviews', array(
			'reviews' => $reviewManager->fetchAllPublishedByPage($page, $config->getPerPageCount()),
			'paginator' => $paginator
		));
	}

	/**
	 * Adds a review by the user
	 * 
	 * @return string The response
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {
			$data = array_merge($this->request->getPost(), $this->request->getClientIP())

			if ($this->getReviewsManager()->send($data), $this->getConfig()->getEnabledModeration()) {
				$this->flashBag->set('success', 'Your reviews has been sent! Thank you');
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
					'name' => new Pattern\Name()
				)
			)
		));
	}
}
