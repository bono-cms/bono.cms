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

use Cms\Controller\Admin\AbstractController;
use Krystal\Tree\AdjacencyList\TreeBuilder;
use Krystal\Tree\AdjacencyList\Render\PhpArray;
use Krystal\Validate\Pattern;

abstract class AbstractProduct extends AbstractController
{
	/**
	 * Builds and populates a form
	 * 
	 * @param $product
	 * @return \Shop\View\Form\ProductForm
	 */
	protected function getForm($product = null)
	{
		$form = $this->makeForm('\Shop\View\Form\ProductForm');

		// Categories are required for both add and edit forms. Therefore declaring as a global
		$form->setData('categories', $this->getCategoryManager()->fetchAllAsTree());

		// If that's an object, then its for edit form
		if ($product) {

			// On edit form, we also need to grab all available photos for a product
			$form->setData('photos', $this->getProductManager()->fetchAllImagesById($product->getId()));
			$form->populate(function() use ($product){
				return array(

					'id' => $product->getId(),
					'cover' => $product->getCover(),
					'web_page_id' => $product->getWebPageId(),
					'title' => $product->getTitle(),
					'regular_price' => $product->getPrice(),
					'stoke_price' => $product->getStokePrice(),
					'category_id' => $product->getCategoryId(),
					'description' => $product->getDescription(),
					'special_offer' => $product->getSpecialOffer(),
					'order' => $product->getOrder(),
					'published' => $product->getPublished(),
					'seo' => $product->getSeo(),
					'slug' => $product->getSlug(),
					'keywords' => $product->getKeywords(),
					'meta_description' => $product->getMetaDescription()
				);
			});
		}

		return $form;
	}

	/**
	 * Returns configured validator instance
	 * 
	 * @param array $input Raw input data
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input['product'],
				'definition' => array(
					'title' => new Pattern\Title(),
					'regular_price' => new Pattern\Price(),
					'description' => new Pattern\Description()
				)
			)
		));
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->load(array('preview', $this->getWysiwygPluginName()))
				   ->appendScript($this->getWithAssetPath('/admin/product.form.js'))
				   ->appendStylesheet($this->getWithAssetPath('/admin/product.form.css'));
	}

	/**
	 * Returns shared variables for Add and Edit controllers
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getSharedVars(array $overrides)
	{
		$shop = $this->moduleManager->getModule('Shop');

		// Grab a service
		$productManager = $shop->getService('productManager');
		//$treeBuilder = new TreeBuilder($shop->getService('categoryManager')->fetchAll());

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Shop',
				'link' => 'Shop:Admin:Browser@indexAction'
			),
			array(
				'name' => $overrides['title'],
				'link' => '#'
			)
		));

		$vars = array(
			//'categories' => $treeBuilder->render(new PhpArray('title')),
			'config' => $shop->getService('configManager')->getEntity()
		);

		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Returns product manager
	 * 
	 * @return \Shop\Service\ProductManager
	 */
	final protected function getProductManager()
	{
		return $this->moduleManager->getModule('Shop')->getService('productManager');
	}

	/**
	 * Returns product manager
	 * 
	 * @return \Shop\Service\ProductManager
	 */
	final protected function getCategoryManager()
	{
		return $this->moduleManager->getModule('Shop')->getService('categoryManager');
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'product.form';
	}
}
