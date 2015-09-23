<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Notifications extends AbstractController
{
	/**
	 * Shows notification's grid
	 * 
	 * @param integer $page Current page
	 * @return void
	 */
	public function indexAction($page = 1)
	{
		$this->loadPlugins();

		$notificationManager = $this->getNotificationManager();

		$paginator = $notificationManager->getPaginator();
		$paginator->setUrl('/admin/notifications/page/%s');

		// This is a very special case, so it needs to be rendered like this
		$response = $this->view->render('notifications', array(
			
			'title' => 'Notifications',
			'dateFormat' => 'd.m.y H:i:s',
			'notifications' => $notificationManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
		));

		$notificationManager->nullify();
		return $response;
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/notifications.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Notifications'
			)
		));
	}

	/**
	 * Returns notification manager
	 * 
	 * @return \Admin\Service\NotificationManager
	 */
	private function getNotificationManager()
	{
		return $this->getService('Cms', 'notificationManager');
	}

	/**
	 * Removes selected notification
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getNotificationManager()->deleteById($id)) {

				$this->flashBag->set('success', 'Selected notification has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Clears all notifications
	 * 
	 * @return string
	 */
	public function clearAction()
	{
		if ($this->getNotificationManager()->clearAll()) {

			$this->flashBag->set('success', 'All notifications have been removed');
			return '1';
		}
	}
}
