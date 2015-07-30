<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Service;

use Menu\Storage\ItemMapperInterface;

final class MenuWidget extends AbstractItemService implements MenuWidgetInterface
{
	/**
	 * Any compliant item mapper which implements ItemMapperInterface
	 * 
	 * @var \Menu\Storage\ItemMapperInterface
	 */
	private $itemMapper;

	/**
	 * State initialization
	 * 
	 * @param \Menu\Storage\ItemMapperInterface $itemMapper
	 * @return void
	 */
	public function __construct(ItemMapperInterface $itemMapper)
	{
		$this->itemMapper = $itemMapper;
	}

	/**
	 * Fetches only one empty item entity
	 * 
	 * @return array
	 */
	public function fetchAllAsOneDummy()
	{
		return array(
			$this->fetchDummy()
		);
	}

	/**
	 * Extracts only ids from item entities
	 * 
	 * @param array $items Item entities collection
	 * @return array
	 */
	public function getIdsFromEntities(array $items)
	{
		$ids = array();

		foreach ($items as $item) {
			array_push($ids, $item->getId());
		}

		return $ids;
	}

	/**
	 * Fetches all menu items associated with given web page id
	 * 
	 * @param string $webPageId
	 * @return array
	 */
	public function fetchAllByWebPageId($webPageId)
	{
		return $this->prepareResults($this->itemMapper->fetchAllByWebPageId($webPageId));
	}

	/**
	 * Removes an item by its associated web page id
	 * 
	 * @param string $webPageId
	 * @return boolean
	 */
	public function deleteAllByWebPageId($webPageId)
	{
		return $this->itemMapper->deleteAllByWebPageId($webPageId);
	}

	/**
	 * Updates a collection of widgets
	 * 
	 * @param array $input Raw widget data which comes from a form
	 * @param string $webPageId
	 * @param string $name Item's name
	 * @return boolean
	 */
	public function update(array $input, $webPageId, $name)
	{
		if (isset($input['widget'], $input['attached'])) {
			$attachedIds = json_decode($input['attached']);

			$items = $this->normalizeInput($input['widget']);

			// We might have removed items
			$removedIds = $this->getRemovedIds($items, $attachedIds);

			// So if we had them, now remove them in back-end's side
			if (!empty($removedIds)) {
				foreach ($removedIds as $removedId) {
					// Otherwise that item was cancelled, and therefore needs to be removed
					$this->itemMapper->deleteById($removedId) && $this->itemMapper->deleteAllByParentId($removedId);
				}
			}

			// And finally it's time to insert or update a widget
			foreach ($items as $item) {
				$item = $this->prepareItem($item, $webPageId, $name);

				// When id is empty, it means that a new item was added
				if (empty($item['id'])) {
					$this->itemMapper->insert($item);
				} else {
					// Otherwise it was existing item
					$this->itemMapper->update($item);
				}
			}

		} else {
			// All menus were removed, therefore simply remove all items associated items with target web page id
			$this->itemMapper->deleteAllByWebPageId($webPageId);
		}

		return true;
	}

	/**
	 * Adds an item from a widget
	 * This method usually invoked from within another module services
	 * 
	 * @param array $input
	 * @param string $webPageId
	 * @param string $name
	 * @return boolean
	 */
	public function add(array $input, $webPageId, $name)
	{
		$items = $this->normalizeInput($input);

		foreach ($items as $item) {
			$item = $this->prepareItem($item, $webPageId, $name);
			$this->itemMapper->insert($item);
		}

		return true;
	}

	/**
	 * Returns removed ids
	 * 
	 * @param array $items
	 * @param array $attached
	 * @return array
	 */
	private function getRemovedIds(array $items, array $attached)
	{
		// No items means that all ids are removed
		if (empty($items)) {
			// So simply return them
			return $attached;
		}

		$result = array();

		// First of all, let's extract current ids we have
		$inputIds = array();

		foreach($items as $item) {
			array_push($inputIds, $item['id']);
		}

		// What id doesn't exist in $items, then it must be considered as removed
		foreach ($attached as $id) {
			if (!in_array($id, $inputIds)) {
				// This id was market to remove, so append it to result
				array_push($result, $id);
			}
		}

		return $result;
	}

	/**
	 * Prepares item array before sending it to a mapper
	 * 
	 * @param array $item
	 * @param string $webPageId
	 * @param string $name
	 * @return array
	 */
	private function prepareItem(array $item, $webPageId, $name)
	{
		if (empty($item['name'])) {
			$item['name'] = $name;
		}

		// Handle internal fields
		$item['link'] = '';
		$item['has_link'] = '0';
		$item['web_page_id'] = $webPageId;
		$item['hint'] = '';

		return $item;
	}

	/**
	 * Normalizes input array
	 * 
	 * @param array $array
	 * @return array
	 */
	private function normalizeInput(array $array)
	{
		// This is what we're going to return
		$output = array();

		// First of all, we need to count amount of values
		// In order to create amount of keys in resulting array
		$count = 0;

		// Grab only one nested array to count its values
		// That's enough, since we assume that amount of keys is the same
		$targetValues = array_values($array);
		$target = array_pop($targetValues);

		// No longer need it
		unset($targetValues);

		// This way we can quickly count amount of value keys
		foreach ($target as $value) {
			$count++;
		}

		// Now get available keys
		$keys = array_keys($array);

		// Save keys count
		$keysCount = count($keys);

		for ($i = 0; $i < $count; $i++) {
			for ($k = 0; $k < $keysCount; $k++) {
				// Create index on demand
				if (!isset($output[$i])) {
					$output[$i] = array();
				}

				// Current key and value
				$key =& $keys[$k];
				$value =& $array[$key][$i];
				
				$output[$i][$key] = $value;
			}
		}

		return $output;
	}
}
