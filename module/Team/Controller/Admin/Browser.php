<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Team\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
	/**
	 * Shows a grid
	 * 
	 * @param integer $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->loadPlugins();

		$teamManager = $this->getTeamManager();

		$paginator = $teamManager->getPaginator();
		$paginator->setUrl('/admin/module/team/page/%s', $page);

		return $this->view->render('browser', array(
			'members' => $teamManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
			'title' => 'Team'
		));
	}

	/**
	 * Returns team manager
	 * 
	 * @return \Team\Service\TeamManager
	 */
	private function getTeamManager()
	{
		return $this->getModuleService('teamManager');
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Team',
				'link' => '#'
			)
		));
	}

	/**
	 * Removes selected team's member
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getTeamManager()->deleteById($id)) {

				$this->flashMessenger->set('success', 'Selected team member has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Removes selected members
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$ids = array_keys($this->request->getPost('toDelete'));
			$this->getTeamManager()->deleteByIds($ids);

			$flashKey = 'success';
			$flashMessage = 'Selected team member have been removed successfully';

		} else {

			$flashKey = 'warning';
			$flashMessage = 'You should select at least one member to remove';
		}

		$this->flashMessenger->set($flashKey, $flashMessage);
		return '1';
	}

	/**
	 * Save changes
	 * 
	 * @return string The response
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('published', 'order')) {

			$published = $this->request->getPost('published');
			$orders = $this->request->getPost('order');

			$teamManager = $this->getTeamManager();

			// Now start updating
			$teamManager->updateOrders($orders);
			$teamManager->updatePublished($published);

			$this->flashMessenger->set('success', 'Settings have been updated successfully');

			return '1';
		}
	}
}
