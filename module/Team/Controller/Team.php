<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Controller;

use Site\Controller\AbstractController;

final class Team extends AbstractController
{
	/**
	 * Shows a page with team members
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

			$teamManager = $this->getModuleService('teamManager');
			$config = $this->getModuleService('configManager')->getEntity();

			// Fetch all members
			$members = $teamManager->fetchAllPublishedByPage($pageNumber, $config->getPerPageCount());

			$paginator = $teamManager->getPaginator();
			$this->preparePaginator($paginator, $code, $slug, $pageNumber);

			return $this->view->render('team', array(
				'page' => $page,
				'members' => $members,
				'paginator' => $paginator
			));

		} else {

			return false;
		}
	}
}
