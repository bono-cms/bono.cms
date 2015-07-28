<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Aboutbox\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Config extends AbstractController
{
	/**
	 * Shows about box
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadPlugins();

		return $this->view->render('about-box', array(
			'title' => 'About box',
			'content' => $this->getAboutBoxManager()->fetch(),
		));
	}

	/**
	 * Saves data
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('content')) {

			$content = $this->request->getPost('content');

			if ($this->getAboutBoxManager()->update($content)) {
				$this->flashBag->set('success', 'About box has been updated successfully');
			}

			return '1';
		}
	}

	/**
	 * Returns box manager
	 * 
	 * @return \AboutBox\Service\AboutBoxManager
	 */
	private function getAboutBoxManager()
	{
		return $this->getModuleService('aboutBoxManager');
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->load($this->getWysiwygPluginName())
				   ->appendScript($this->getWithAssetPath('/admin/box.form.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'About box'
			)
		));
	}
}
