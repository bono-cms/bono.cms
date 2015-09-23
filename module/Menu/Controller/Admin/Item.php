<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Controller\Admin;

use Krystal\Tree\AdjacencyList\Render\PhpArray;

final class Item extends AbstractItem
{
	/**
	 * Shows main form, selecting parent item id
	 * 
	 * @param string $categoryId Current category id
	 * @param string $parentId Parent item id to be selected
	 * @return string
	 */
	public function addChildAction($categoryId, $parentId)
	{
		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars($categoryId, array(
			'maxDepth' => $this->getMaxNestedDepth($categoryId),

			// Tells whether we in edit mode. By using this variable we can re-use the same template
			'editing' => false,
			'helper_title' => 'Add new item',
			'item' => $this->getDummyItemBag($categoryId, $parentId)
		)));
	}

	/**
	 * Shows items and categories
	 * 
	 * @param string $categoryId When category id is null, then its replaced by the last one automatically
	 * @return string
	 */
	public function indexAction($categoryId = null)
	{
		// When its null, then we are on default page
		if (is_null($categoryId)) {
			$categoryId = $this->getLastCategoryId();
		}

		if ($categoryId) {
			if (!$this->flashBag->has('success')) {
				$this->flashBag->set('info', 'Just drag and drop items the way you like. To get options, just do a right click on desired item');
			}
		}

		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars($categoryId, array(

			'maxDepth' => $this->getMaxNestedDepth($categoryId),

			// Tells whether we in edit mode. By using this variable we can re-use the same template
			'editing' => false,
			'helper_title' => 'Add new item',
			'item' => $this->getDummyItemBag($categoryId)
		)));
	}

	/**
	 * Browses by category's id
	 * 
	 * @param string $Id Category id
	 * @return string
	 */
	public function categoryAction($id)
	{
		return $this->indexAction($id);
	}

	/**
	 * Shows item data by its associated id
	 * 
	 * @param string $id Item id
	 * @return string
	 */
	public function viewAction($id)
	{
		// Try to grab item's entity
		$item = $this->getItemManager()->fetchById($id);

		// If it's not false, then id is valid
		if ($item !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars($item->getCategoryId(), array(

				'maxDepth' => $this->getMaxNestedDepth($item->getCategoryId()),
				'item' => $item,
				// Tells whether we in edit mode. By using this variable we can re-use the same template
				'editing' => true,
				'helper_title' => 'Edit the item',

			), $id));

		} else {
			return false;
		}
	}

	/**
	 * Adds an item
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$itemManager = $this->getItemManager();

		$itemManager->add($this->request->getPost());
		$this->flashBag->set('success', 'An item has been created successfully!');

		return $itemManager->getLastId();
	}

	/**
	 * Updates an item
	 * 
	 * @return string The response
	 */
	public function updateAction()
	{
		$this->getItemManager()->update($this->request->getPost());
		$this->flashBag->set('success', 'An item has been updated successfully!');

		return '1';	
	}

	/**
	 * Saves what has been "dragged and dropped"
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('json_data')) {
			$jsonData = $this->request->getPost('json_data');

			return $this->getItemManager()->save($jsonData);
		}
	}

	/**
	 * Deletes an item by its associated id and its children if has
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			$this->getItemManager()->deleteById($id);
			$this->flashBag->set('success', 'The item has been removed successfully!');

			return '1';
		}
	}

	/**
	 * Deletes a category by its associated id
	 * 
	 * @return string
	 */
	public function deleteCategoryAction()
	{
		if ($this->request->hasPost('id')) {

			$id = $this->request->getPost('id');

			// Grab a service
			$categoryManager = $this->moduleManager->getModule('Menu')->getService('categoryManager');
			$categoryManager->deleteById($id);

			$this->flashBag->set('success', 'The category has been removed successfully');

			return '1';
		}
	}
}
