<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace QA\Controller\Admin;

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

		$qaManager = $this->getQaManager();

		$paginator = $qaManager->getPaginator();
		$paginator->setUrl('/admin/module/qa/page/%s');

		return $this->view->render('browser', array(
			'title' => 'Questions and Answers',
			'pair' => $qaManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator' => $paginator,
		));
	}

	/**
	 * Returns QA manager
	 * 
	 * @return \QA\Service\QaManager
	 */
	private function getQaManager()
	{
		return $this->moduleManager->getModule('QA')->getService('qaManager');
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
				'link' => '#',
				'name' => 'Questions and Answers'
			)
		));
	}

	/**
	 * Removes a pair
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getQaManager()->deleteById($id)) {

				$this->flashMessenger->set('success', 'Selected pair has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Removes posts by their associated ids
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {
			
			$ids = array_keys($this->request->getPost('toDelete'));
			
			$this->getQaManager()->deleteByIds($ids);
			
			$flashKey = 'success';
			$flashMessage = 'Selected pairs have been removed successfully';
			
		} else {
			
			$flashKey = 'warning';
			$flashMessage = 'You should select at least one pair to remove';
		}
		
		$this->flashMessenger->set($flashKey, $flashMessage);
		return '1';
	}

	/**
	 * Saves options
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('published')) {
			$published = $this->request->getPost('published');

			$this->getQaManager()->updatePublished($published);
			$this->flashMessenger->set('success', 'Settings have been successfully');

			return '1';
		}
	}
}
