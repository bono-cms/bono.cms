<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Qa\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
	/**
	 * Shows a grid
	 * 
	 * @param integer $page Current page number
	 * @return string
	 */
	public function indexAction($pageNumber = 1)
	{
		$this->loadPlugins();

		$qaManager = $this->getQaManager();

		$paginator = $qaManager->getPaginator();
		$paginator->setUrl('/admin/module/qa/page/(:var)');

		return $this->view->render('browser', array(
			'title' => 'Questions and Answers',
			'pairs' => $qaManager->fetchAllByPage($pageNumber, $this->getSharedPerPageCount()),
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
		return $this->getModuleService('qaManager');
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

				$this->flashBag->set('success', 'Selected pair has been removed successfully');
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

			$this->flashBag->set('success', 'Selected pairs have been removed successfully');
		} else {
			$this->flashBag->set('warning', 'You should select at least one pair to remove');
		}

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
			$this->flashBag->set('success', 'Settings have been updated successfully');

			return '1';
		}
	}
}
