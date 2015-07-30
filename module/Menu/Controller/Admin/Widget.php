<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Controller\Admin;

use Krystal\Tree\AdjacencyList\Render\PhpArray;

final class Widget extends AbstractItem
{
	/**
	 * Return items with empty prep-ended values
	 * 
	 * @param array $items
	 * @return array
	 */
	final protected function getWithPrependedEmpty($items)
	{
		// array_merge breaks the indexes (i.e parent ids), so in this case we'd simply sum arrays
		return array('0' => $this->translator->translate('— None —')) + $items;
	}

	/**
	 * Loads items attached to category id
	 * 
	 * @return string
	 */
	public function loadCategoryItemsAction()
	{
		$categoryId = $this->request->getPost('category_id');
		$items = $this->getTreeBuilder($categoryId)->render(new PhpArray('name'));

		return json_encode($this->getWithPrependedEmpty($items));
	}

	/**
	 * Loads menu widget
	 * 
	 * @param string $webPageId
	 * @return string
	 */
	public function loadWigdetAction($webPageId = null)
	{
		$categories = $this->getCategoryManager()->fetchAll();

		if (!isset($categories[0])) {
			return null;
		}

		// Grab first category's id
		$categoryId = $categories[0]->getId();
		$treeBuilder = $this->getTreeBuilder($categoryId);

		$menuWidget = $this->getMenuWidget();

		if (is_null($webPageId)) {
			$items = $menuWidget->fetchAllAsOneDummy();
		} else {
			$items = $menuWidget->fetchAllByWebPageId($webPageId);

			// No such web page? OK, then quickly workaround this
			if (!$items) {
				$items = array();
			}
		}

		return $this->view->disableLayout()->render('widget', array(

			'attached' => json_encode($menuWidget->getIdsFromEntities($items)),
			'items' => $items,
			'categories' => $categories,

			// Fetch items of first category
			'parentItems' => $this->getWithPrependedEmpty($this->getTreeBuilder($categoryId)->render(new PhpArray('name'))),

			'publishOptions' => array(
				'1' => "Yes, publish immediately",
				'0' => "No, don't publish now"
			),
			
			'newWindowOptions' => array(
				'0' => 'Open in the same window',
				'1' => 'Open in new window'
			)
		));
	}
}
