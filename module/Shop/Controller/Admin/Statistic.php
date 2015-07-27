<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Statistic extends AbstractController
{
	/**
	 * Default action
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		return $this->view->disableLayout()->render('statistic', array(
			'data' => $this->getData()
		));
	}

	/**
	 * Returns statistic data
	 * 
	 * @return array
	 */
	private function getData()
	{
		$currency = $this->getModuleService('configManager')->getEntity()->getCurrency();

		return array(
			'Total categories' => $this->getModuleService('categoryManager')->countAll(),
			'Total products' => $this->getModuleService('productManager')->countAll(),
			'Currency' => $currency,
			'Total orders' => $this->getModuleService('orderManager')->countAll(false),
			'Approved orders' => $this->getModuleService('orderManager')->countAll(true),
			'Total amount sold products' => $this->getModuleService('orderManager')->getQtySumCount(),
			'Total sum of sold products' => $this->getModuleService('orderManager')->getPriceSumCount().PHP_EOL.$currency,
		);
	}
}
