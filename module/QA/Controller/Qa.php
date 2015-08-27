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
	 * Lists all pairs with pagination
	 * 
	 * @param string $id Page id
	 * @param string $pageNumber Page id
	 * @param string $code Language code
	 * @param string $slug Page slug
	 * @return string
	 */
	public function indexAction($id, $pageNumber = 1, $code = null, $slug = null)
	{
		$pageManager = $this->getService('Pages', 'pageManager');
		$page = $pageManager->fetchById($id);

		if ($page !== false) {

			// Load asset plugins and tweak breadcrumbs
			$this->loadSitePlugins();
			$this->view->getBreadcrumbBag()->add($pageManager->getBreadcrumbs($page));

			$qaManager = $this->getModuleService('qaManager');
			$config = $this->getModuleService('configManager')->getEntity();

			// Tweak pagination service
			$paginator = $qaManager->getPaginator();
			$this->preparePaginator($paginator, $code, $slug, $pageNumber);
			
			return $this->view->render('qa', array(
				'pairs' => $qaManager->fetchAllPublishedByPage($pageNumber, $config->getPerPageCount()),
				'paginator' => $paginator,
			));

		} else {

			return false;
		}
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
