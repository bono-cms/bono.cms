<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Faq\Controller\Admin;

final class Browser extends AbstractAdminController
{
	/**
	 * Shows a table
	 * 
	 * @param string $page Current page
	 * @return string
	 */ 
	public function indexAction($page = 1)
	{
		$faqManager = $this->getFaqManager();

		$this->loadPlugins();

		$paginator = $faqManager->getPaginator();
		$paginator->setUrl('/admin/module/faq/page/%s');

		return $this->view->render('browser', array(
			'paginator'	=> $paginator,
			'faqs' => $faqManager->fetchAllByPage($page, $this->getSharedPerPageCount(), false),
			'title' => 'FAQ',
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
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'FAQ',
				'link' => '#'
			)
		));
	}

	/**
	 * Delete selected FAQs by their associated ids
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			$this->getFaqManager()->deleteByIds(array_keys($this->request->getPost('toDelete')));
			$this->flashBag->set('success', 'Selected FAQS have been removed successfully');
			
		} else {
			$this->flashBag->set('warning', 'You should select at least one FAQ to remove');
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
		if ($this->request->hasPost('published', 'order') && $this->request->isAjax()) {

			$published = $this->request->getPost('published');
			$orders = $this->request->getPost('order');

			$faqManager = $this->getFaqManager();

			$faqManager->updatePublished($published);
			$faqManager->updateOrders($orders);

			$this->flashBag->set('success', 'Settings have been save successfully');

			return '1';
		}
	}

	/**
	 * Deletes a FAQ by its associated id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id') && $this->request->isAjax()) {

			$id = $this->request->getPost('id');

			if ($this->getFaqManager()->deleteById($id)) {

				$this->flashBag->set('success', 'The FAQ has been removed successfully');
				return '1';
			}
		}
	}
}
