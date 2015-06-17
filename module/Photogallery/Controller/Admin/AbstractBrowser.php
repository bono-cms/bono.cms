<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Tree\AdjacencyList\TreeBuilder;
use Krystal\Tree\AdjacencyList\Render\PhpArray;

abstract class AbstractBrowser extends AbstractController
{
	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharePlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'))
				   ->load('zoom');
	}

	/**
	 * Returns shared variables
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getSharedVars(array $overrides)
	{
		$photogallery = $this->moduleManager->getModule('Photogallery');
		$treeBuilder = new TreeBuilder($photogallery->getService('albumManager')->fetchAll());

		$title = 'Photogallery';

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => $title
			)
		));

		$vars = array(
			'taskManager' => $photogallery->getService('taskManager'),
			'albums' => $treeBuilder->render(new PhpArray('title')),
			'title' => $title
		);

		return array_replace_recursive($vars, $overrides);
	}

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
	 * Returns Photo Manager
	 * 
	 * @return \Photogallery\Service\PhotoManager
	 */
	final protected function getPhotoManager()
	{
		return $this->moduleManager->getModule('Photogallery')->getService('photoManager');
	}

	/**
	 * Returns Album Manager
	 * 
	 * @return \Photogallery\Service\AlbumManager
	 */
	final protected function getAlbumManager()
	{
		return $this->moduleManager->getModule('Photogallery')->getService('albumManager');
	}
}
