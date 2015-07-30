<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa\Controller;

use Site\Controller\AbstractController;

final class Qa extends AbstractController
{
	/**
	 * Default action
	 * Lists all pairs with pagination
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		// Grab a module
		$module = $this->moduleManager->getModule('Qa');
		$qaManager = $module->getService('qaManager');

		return $this->view->render('qa', array(
			
			'pairs' => $qaManager->fetchAllByPage($page, 10),
			'paginator' => $qaManager->getPaginator(),
		));
	}

	/**
	 * Handles form submission
	 * 
	 * @return string
	 */
	public function submitAction()
	{
		if ($this->request->isPost() && $this->request->isAjax()) {
			
		}
	}
}
