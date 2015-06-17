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

final class Edit extends AbstractReview
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id Reviews's id
	 * @return string
	 */
	public function indexAction($id)
	{
		$review = $this->getReviewsManager()->fetchById($id);

		if ($review !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'editing' => true,
				'title' => 'Edit the review',
				'review' => $review
			)));

		} else {
			return false;
		}
	}

	/**
	 * Updates a review
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			if ($this->getReviewsManager()->update($this->getContainer())) {
				
				$this->flashMessenger->set('success', 'The review has been updated successfully');
				return '1';
			}
			
		} else {
			
			return $formValidator->getErrors();
		}
	}
}
