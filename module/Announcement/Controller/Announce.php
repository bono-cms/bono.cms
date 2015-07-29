<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Controller;

use Site\Controller\AbstractController;

final class Announce extends AbstractController
{
	/**
	 * Renders an announce by its associated id
	 * 
	 * @param string $id Announce id
	 * @return string
	 */
	public function indexAction($id)
	{
		$announceManager = $this->getModuleService('announceManager');
		$announce = $announceManager->fetchById($id);

		if ($announce !== false) {
			return $this->view->render('page', array(
				'breadcrumbs' => $announceManager->getBreadcrumbs($announce),
				'page' => $announce
			));

		} else {
			return false;
		}
	}
}
