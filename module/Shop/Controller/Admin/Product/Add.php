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

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractProduct
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		$product = new VirtualEntity();
		$product->setSeo(true)
			    ->setPublished(true)
				->setSpecialOffer(false);

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'title' => 'Add a product',
			'product' => $product
		)));
	}

	/**
	 * Adds a product
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$productManager = $this->getProductManager();
			$productManager->add($this->request->getAll());

			$this->flashBag->set('success', 'A product has been added successfully');

			return $productManager->getLastId();

		} else {

			return $formValidator->getErrors();
		}
	}
}
