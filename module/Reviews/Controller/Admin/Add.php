<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Controller\Admin;

final class Add extends AbstractReview
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
			'title' => 'Add new review',
			'review' => $this->getReviewsManager()->fetchDummy()
		)));
	}

	/**
	 * Adds a review
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$reviewsManager = $this->getReviewsManager();

			if ($reviewsManager->add($this->getContainer())) {

				$this->flashMessenger->set('success', 'A review has been added successfully');
				return $reviewsManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
