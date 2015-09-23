<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\Service;

use Krystal\Application\Module\ModuleManagerInterface;
use Menu\Contract\MenuAwareManager;
use Cms\Service\WebPageManagerInterface;
use Closure;

final class LinkBuilder implements LinkBuilderInterface
{
	/**
	 * Web page manager
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * All registered services
	 * 
	 * @var array
	 */
	private $services = array();

	/**
	 * State initialization
	 * 
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function __construct(WebPageManagerInterface $webPageManager)
	{
		$this->webPageManager = $webPageManager;
	}

	/**
	 * Loads data from array of link definitions
	 * 
	 * @param array $definitions
	 * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManager
	 * @return void
	 */
	public function loadFromDefiniton(array $definitions, ModuleManagerInterface $moduleManager)
	{
		foreach ($definitions as $definition) {

			if (isset($definition['module']) && $moduleManager->isLoaded($definition['module'])) {
				if (isset($definition['services']) && is_array($definition['services'])) {
					foreach ($definition['services'] as $name => $service) {
						$this->addService($name, $moduleManager->getModule($definition['module'])->getService($service));
					}
				}
			}
		}
	}

	/**
	 * Extract links from registered services
	 * 
	 * @return array
	 */
	public function collect()
	{
		$raw = $this->webPageManager->fetchAll();
		$data = $this->process($raw);

		return $this->prepareResult($data);
	}

	/**
	 * Returns a service
	 * 
	 * @param string $module
	 * @return object
	 */
	public function getService($module)
	{
		if (isset($this->services[$module])) {
			return $this->services[$module];
		} else {
			return false;
		}
	}

	/**
	 * Adds a service
	 * 
	 * @param MenuAwareManager $service Any service that implements this interface
	 * @return \Menu\Service\LinkBuilder
	 */
	public function addService($module, MenuAwareManager $service)
	{
		$this->services[$module] = $service;
		return $this;
	}

	/**
	 * Adds service collection
	 * 
	 * @param array $services
	 * @return \Menu\Service\LinkBuilder
	 */
	public function addServices(array $services)
	{
		foreach ($services as $module => $service) {
			$this->addService($module, $service);
		}

		return $this;
	}

	/**
	 * Prepares processed result-set
	 * 
	 * @param array $data
	 * @return array
	 */
	private function prepareResult(array $data)
	{
		$result = array();

		foreach ($data as $module => $collection) {
			// Make sure a module label exists, if it doesn't, then create a new one
			if (!isset($result[$module])) {
				$result[$module] = array();
			}

			$result[$module] = $collection;
		}

		return $result;
	}

	/**
	 * Separates an array by its containing key into a new array
	 * Where its separated key becomes a new key, while the rest becomes as a value
	 * 
	 * @param string $key Target key $data
	 * @param array $data
	 * @return array
	 */
	private function separateByKey($key, array $data)
	{
		// There's no need to check if key exists, since this method is always called for existing keys
		$context = $data[$key];
		return array($context => $data);
	}

	/**
	 * Drops raw data into sub-modules
	 * 
	 * @param array $raw Raw data that directly comes from a mapper
	 * @return array Dropped array
	 */
	private function drop(array $raw)
	{
		$result = array();

		foreach ($raw as $index => $collection) {
			// Extract module' name as a key and put the rest into its values
			$target = $this->separateByKey('module', $collection);

			foreach ($target as $module => $array) {
				// When doesn't exist, then need to create a one
				if (!isset($result[$module])) {
					$result[$module] = array();
				}

				$result[$module][] = $array;
			}
		}

		return $result;
	}

	/**
	 * Appends data into nested dropped array using a visitor
	 * 
	 * @param array $data
	 * @param \Closure $visitor for each module's node to apply
	 * @return array
	 */
	private function appendNestedPair(array $data, Closure $visitor)
	{
		$result = array();

		foreach ($data as $module => $options) {
			foreach ($options as $index => $collection) {
				// Attempt to grab data
				$data = $visitor($collection);

				// If $data has something, then we'd start processing its block
				if (!empty($data)) {
					if (!isset($result[$module])) {
						$result[$module] = array();
					}

					// Assign visitor's returned value
					$result[$module][] = $data;
				}
			}
		}

		return $result;
	}

	/**
	 * Processes the raw data
	 * 
	 * @param array $raw Data that comes from storage
	 * @return array Processed and ready to be iterated over
	 */
	private function process(array $raw)
	{
		// This trick allows to use class's public methods inside a visitor at least
		$that = $this;

		$data = $this->appendNestedPair($this->drop($raw), function($data) use ($that){
			// Grab a service for current module
			$service = $that->getService($data['module']);

			// Ensure found service is registered in module declaration
			if ($service !== false) {

				// Append a new key, called title
				$data['title'] = $service->fetchTitleByWebPageId($data['id']);
				return $data;
			}
		});

		return $data;
	}
}
