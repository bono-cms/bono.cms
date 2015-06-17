<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Announcement\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

abstract class AbstractBrowser extends AbstractController
{
	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'browser';
	}
	
	/**
	 * Returns announcement module
	 * 
	 * @return \Announcement\Module
	 */
	final protected function getAnnouncementModule()
	{
		return $this->moduleManager->getModule('Announcement');
	}

	/**
	 * Returns announce manager
	 * 
	 * @return \Announcement\Service\AnnounceManager
	 */
	final protected function getAnnounceManager()
	{
		return $this->getAnnouncementModule()->getService('announceManager');
	}

	/**
	 * Returns category manager
	 * 
	 * @return \Announcement\Service\CategoryManager
	 */
	final protected function getCategoryManager()
	{
		return $this->getAnnouncementModule()->getService('categoryManager');
	}

	/**
	 * Returns shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getSharedVars(array $overrides)
	{
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Announcement'
			)
		));

		$vars = array(
			'categories' => $this->getCategoryManager()->fetchAll(),
			'title' => 'Announcements',
		);

		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));
	}
}
