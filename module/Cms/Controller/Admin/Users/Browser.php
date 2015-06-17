<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin\Users;

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
	/**
	 * Shows a table
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadPlugins();

		return $this->view->render('users/browser', array(
			'users' => $this->getCmsModule()->getService('userManager')->fetchAll(),
			'title' => 'Users',
		));
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/users/browser.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Users'
			)
		));
	}

	/**
	 * Removes selected user
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			// Grab a service
			if ($this->getCmsModule()->getService('userManager')->deleteById($id)) {

				$this->flashMessenger->set('success', 'Selected user has been removed successfully');
				return '1';
			}
		}
	}
}
