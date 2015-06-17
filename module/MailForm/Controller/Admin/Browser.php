<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
	/**
	 * Shows a grid
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadPlugins();

		return $this->view->render('browser', array(

			'forms' => $this->getFormManager()->fetchAll(),
			'title' => 'Mail forms'
		));
	}

	/**
	 * Deletes a form by its associated id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getFormManager()->deleteById($id)) {
				$this->flashMessenger->set('success', 'Selected form has been removed successfully');
				return '1';
			}
		}
	}

	/**
	 * Deletes selected forms by their associated id
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {
			$ids = array_keys($this->request->getPost('toDelete'));

			$this->getFormManager()->deleteByIds($ids);

			$flashKey = 'success';
			$flashMessage = 'Selected forms have been removed successfully';

		} else {

			$flashKey = 'warning';
			$flashMessage = 'You should select at least one form to remove';
		}

		$this->flashMessenger->set($flashKey, $flashMessage);
		return '1';
	}

	/**
	 * Saves changes
	 * 
	 * @return string
	 */
	public function saveChangesAction()
	{
		if ($this->request->hasPost('seo')) {
			$seo = $this->request->getPost('seo');

			if ($this->getFormManager()->updateSeo($seo)) {

				$this->flashMessenger->set('success', 'Settings have been updated successfully');
				return '1';
			}
		}
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Mail forms',
				'link' => '#'
			)
		));
		
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));
	}

	/**
	 * Returns form manager
	 * 
	 * @return \MailForm\Service\FormManager
	 */
	private function getFormManager()
	{
		return $this->moduleManager->getModule('MailForm')->getService('formManager');
	}
}
