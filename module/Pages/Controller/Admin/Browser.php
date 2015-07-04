<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
	/**
	 * Shows a table
	 * 
	 * @param string $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->loadPlugins();

		$pageManager = $this->getPageManager();;

		$paginator = $pageManager->getPaginator();
		$paginator->setUrl('/admin/module/pages/browse/%s');

		return $this->view->render('browser', array(
			'title' => 'Pages',
			'pages' => $pageManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator'	=> $paginator,
		));
	}

	/**
	 * Returns page manager
	 * 
	 * @return \Pages\Service\PageManager
	 */
	private function getPageManager()
	{
		return $this->moduleManager->getModule('Pages')->getService('pageManager');
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/page.browser.js'));
		
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Pages'
			)
		));
	}
	
	/**
	 * Deletes selected page by its associated id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			$this->getPageManager()->deleteById($id);
			$this->flashMessenger->set('success', 'Selected page has been removed successfully');
		}

		return '1';
	}

	/**
	 * Delete selected pages
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$ids = array_keys($this->request->getPost('toDelete'));

			// Flash data
			$type = 'success';
			$message = 'Selected pages have been removed successfully';

			$this->getPageManager()->deleteByIds($ids);

		} else {
			
			// Nothing to delete
			$type = 'warning';
			$message = 'You have not checked any page you want to remove';
		}

		$this->flashMessenger->set($type, $message);
		return '1';
	}

	/**
	 * Saves options
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('default', 'seo')) {

			// Grab request data
			$default = $this->request->getPost('default');
			$seo = $this->request->getPost('seo');

			if ($this->getPageManager()->makeDefault($default) && $this->getPageManager()->updateSeo($seo)) {

				$this->flashMessenger->set('success', 'Settings have been saved successfully');
				return '1';
			}
		}
	}
}
