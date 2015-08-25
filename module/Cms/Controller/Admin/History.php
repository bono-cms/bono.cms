<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class History extends AbstractController
{
	/**
	 * Shows history grid
	 * 
	 * @param integer $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->loadPlugins();

		$historyManager = $this->getService('Cms', 'historyManager');
		// User manager is used for providing user's name by his associated id in iteration
		$userManager = $this->getService('Cms', 'userManager');

		// Tweak paginator
		$paginator = $historyManager->getPaginator();
		$paginator->setUrl('/admin/history/page/(:var)');

		return $this->view->render('history', array(
			'title' => 'History',
			'paginator' => $historyManager->getPaginator(),
			'records'   => $historyManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'userManager' => $userManager
		));
	}

	/**
	 * Clears all history
	 * 
	 * @return string
	 */
	public function clearAction()
	{
		$historyManager = $this->getService('Cms', 'historyManager');

		if ($historyManager->clear()) {

			$this->flashBag->set('success', 'History has been cleared successfully');
			return '1';
		}
	}

	/**
	 * Loads required plugins
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'History'
			)
		));

		$this->view->getPluginBag()->appendScript($this->getWithAssetPath('/admin/history.js'));
	}
}
