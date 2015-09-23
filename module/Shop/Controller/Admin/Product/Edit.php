<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller\Admin\Product;

final class Edit extends AbstractProduct
{
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

			return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
				'product' => $product,
				'title' => 'Edit the product',
				'photos' => $this->getProductManager()->fetchAllImagesById($id),
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

				$this->flashBag->set('success', 'The product has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
