<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Controller;

use Site\Controller\AbstractController;

final class Album extends AbstractController
{
	/**
	 * View albums photos by its id
	 * 
	 * @param string $albumId Album id
	 * @param integer $pageNumber Current page number
	 * @param string $code Language code
	 * @param string $slug Album slug
	 * @return string
	 */
	public function showAction($albumId = false, $pageNumber = 1, $code = null, $slug = null)
	{
		$photoManager = $this->getModuleService('photoManager');
		$albumManager = $this->getModuleService('albumManager');
		$config = $this->getModuleService('configManager')->getEntity();

		// Prepare pagination
		$paginator = $photoManager->getPaginator();
		$this->preparePaginator($paginator, $code, $slug, $pageNumber);

		// Fetch page's entity
		$page = $albumManager->fetchById($albumId);

		if ($page !== false) {

			// Append breadcrumbs to view now
			$this->view->getBreadcrumbBag()->add($albumManager->getBreadcrumbs($page));
			$this->loadSitePlugins();

			// Template variables
			$vars = array(
				'page' => $page,
				'paginator' => $paginator,
				'photos' => $photoManager->fetchAllPublishedByAlbumIdAndPage($albumId, $pageNumber, $config->getPerPageCount()),
			);

			// Try to find child nodes
			$children = $albumManager->fetchChildrenByParentId($albumId);

			// If we have at least one nested album
			if (!empty($children)) {
				// Then append them to view templates as well
				$vars['albums'] = $children;
			}

			return $this->view->render('album', $vars);

		} else {

			return false;
		}
	}
}
