<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Controller;

use Krystal\Stdlib\VirtualEntity;

final class Page extends AbstractPagesController
{
	/**
	 * Renders a page by its associated id
	 * 
	 * @param string $id Page's id
	 * @return string
	 */
	public function indexAction($id)
	{
		// If id is null, then we fetch default page
		if (is_null($id)) {
			$page = $this->getPageManager()->fetchDefault();
		} else {
			$page = $this->getPageManager()->fetchById($id);
		}

		// If $page isn't false, then right $id supplied
		if ($page !== false) {

			$this->loadSitePlugins();
			$this->loadBreadcrumbsByPageEntity($page);

			return $this->view->render($this->grabTemplateName($page), array(
				'page' => $page
			));

		} else {

			// Returning false from controller's action triggers 404 error automatically
			return false;
		}
	}
	
	/**
	 * Grabs template name
	 * 
	 * @param $page
	 * @return string
	 */
	private function grabTemplateName($page)
	{
		if (trim($page->getTemplate()) !== '') {
			return $page->getTemplate();
		} else {
			return 'pages-page';
		}
	}

	/**
	 * Loads breadcrumb by page's entity
	 * 
	 * @param $page
	 * @return void
	 */
	private function loadBreadcrumbsByPageEntity($page)
	{
		// Breadcrumb bag
		$breadcrumbBag = $this->view->getBreadcrumbBag();

		// If page isn't default, then we append a breadcrumb
		if (!$page->getDefault()) {
			$breadcrumbBag->add($this->getPageManager()->getBreadcrumbs($page));
		} else {
			// Otherwise we should never have breadcrumbs
			$breadcrumbBag->clear();
		}
	}

	/**
	 * Not found action
	 * 
	 * @return string
	 */
	public function notFoundAction()
	{
		$this->loadSitePlugins();
		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => '404',
				'link' => '#'
			)
		));
		
		//@TODO That needs to be in response
		header("HTTP/1.0 404 Not Found");		
		
		//@TODO 404
		return $this->view->render('pages-404', array(
			'title' => '404',
			'page' => new VirtualEntity()
		));
	}
}
