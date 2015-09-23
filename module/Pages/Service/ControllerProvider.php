<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\Service;

use Krystal\Application\Route\MapManager;

final class ControllerProvider implements ControllerProviderInterface
{
	/**
	 * Global route map
	 * 
	 * @var array
	 */
	private $routes = array();

	/**
	 * Excluded modules
	 * 
	 * @var array
	 */
	private $excluded = array(
		'Admin', 
		'Cms', 
		'Site'
	);

	/**
	 * State initialization
	 * 
	 * @param array $routes Global route map
	 * @return void
	 */
	public function __construct(array $routes)
	{
		$this->routes = $routes;
	}

	/**
	 * Returns a filtered array of controllers
	 * 
	 * @return array
	 */
	public function getControllers()
	{
		$mapManager = new MapManager($this->routes);
		$result = array();

		foreach ($mapManager->getControllers() as $controller) {
			// Add only non-excluded controller
			if (!$this->isExcluded($controller)) {
				$result[$controller] = $controller;
			}
		}

		return $result;
	}

	/**
	 * Determines whether module
	 * 
	 * @param string $controller Raw controller name
	 * @return boolean
	 */
	private function isExcluded($controller)
	{
		foreach ($this->excluded as $module) {
			if (strpos($controller, $module) !== false) {
				return true;
			}
		}

		return false;
	}
}
