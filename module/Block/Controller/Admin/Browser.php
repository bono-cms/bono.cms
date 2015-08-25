<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Block\Controller\Admin;

final class Browser extends AbstractAdminController
{
	/**
	 * Shows a table
	 * 
	 * @param integer $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->loadPlugins();

		$blockManager = $this->getBlockManager();;

		$paginator = $blockManager->getPaginator();
		$paginator->setUrl('/admin/module/block/page/(:var)');

		return $this->view->render('browser', array(
			'blocks'	=> $blockManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
			'paginator'	=> $paginator,
			'title' => 'HTML Blocks',
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
				'link' => '#',
				'name' => 'HTML Blocks'
			)
		));
	}

	/**
	 * Deletes selected blocks
	 * 
	 * @return string The response
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {
			$ids = array_keys($this->request->getPost('toDelete'));

			$this->getBlockManager()->deleteByIds($ids);
			$this->flashBag->set('success', 'Selected blocks have been removed successfully');

		} else {
			$this->flashBag->set('warning', 'You should select at least one block to remove');
		}

		return '1';
	}

	/**
	 * Deletes a block by its associated id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {
			$id = $this->request->getPost('id');

			if ($this->getBlockManager()->deleteById($id)) {

				$this->flashBag->set('success', 'A block has been removed successfully');
				return '1';
			}
		}
	}
}
