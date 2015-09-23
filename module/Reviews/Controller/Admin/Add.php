<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Reviews\Controller\Admin;

use Krystal\Stdlib\VirtualEntity;

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

		$review = new VirtualEntity();
		$review->setPublished(true)
			   ->setTimestamp(time());

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'title' => 'Add new review',
			'review' => $review
		)));
	}

	/**
	 * Adds a review
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('review'));

		if ($formValidator->isValid()) {

			$reviewsManager = $this->getReviewsManager();

			if ($reviewsManager->add($this->getContainer())) {

				$this->flashBag->set('success', 'A review has been added successfully');
				return $reviewsManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
