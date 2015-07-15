<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Controller;

use Site\Controller\AbstractController;

final class Album extends AbstractController
{
	/**
	 * Shows all available albums
	 * 
	 * @return void
	 */
	public function listAction()
	{
		//@todo
	}

	/**
	 * View albums photos by its id
	 * 
	 * @param string $albumId Album id
	 * @param integer $pageNumber Current page number
	 * @param string $code Language code
	 * @param string $slug Album slug
	 * @return string
	 */
	public function showAction($albumId, $pageNumber = 1, $code = null, $slug = null)
	{
		$photoManager = $this->getModuleService('photoManager');
		$albumManager = $this->getModuleService('albumManager');
		$config = $this->getModuleService('configManager')->getEntity();

		// Prepare pagination
		$paginator = $photoManager->getPaginator();
		$this->preparePaginator($paginator, $code, $slug, $pageNumber);

		// Fetch page's entity
		$page = $albumManager->fetchById($albumId);

		// Append breadcrumbs to view now
		$this->view->getBreadcrumbBag()->add($albumManager->getBreadcrumbs($page));
		$this->loadSitePlugins();

		return $this->view->render('album', array(
			'page' => $page,
			'paginator' => $paginator,
			'photos' => $photoManager->fetchAllPublishedByAlbumIdAndPage($albumId, $pageNumber, $config->getPerPageCount()),
		));
	}
}
