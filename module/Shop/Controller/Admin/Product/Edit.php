<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller\Admin\Product;

final class Edit extends AbstractProduct
{
	/**
	 * Returns image form
	 * 
	 * @return \Shop
	 */
	private function getImageForm()
	{
	}

	/**
	 * Shows edit form
	 * 
	 * @param string $id Product id
	 * @return string
	 */
	public function indexAction($id)
	{
		$product = $this->getProductManager()->fetchById($id);

		if ($product !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'form' => $this->getForm($product),
				'title' => 'Edit the product',
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates a product
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			if ($this->getProductManager()->update($this->request->getAll())) {

				$this->flashMessenger->set('success', 'The product has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
