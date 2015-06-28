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
	 * @param string $pageNumber Page id
	 * @return string
	 */
	public function indexAction($id, $pageNumber = 1)
	{
		$page = $this->getService('Pages', 'pageManager')->fetchById($id);
		$this->loadSitePlugins();

		$teamManager = $this->getModuleService('teamManager');

		$paginator = $teamManager->getPaginator();

		return $this->view->render('team', array(
			'page' => $page,
			//@TODO per page count
			'members' => $teamManager->fetchAllPublishedByPage($pageNumber, 10)
		));
	}
}
