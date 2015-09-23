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

use Menu\Contract\MenuAwareManager;
use Krystal\Application\Module\ModuleManagerInterface;

interface LinkBuilderInterface
{
	/**
	 * Loads data from array of link definitions
	 * 
	 * @param array $definitions
	 * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManager
	 * @return void
	 */
	public function loadFromDefiniton(array $definitions, ModuleManagerInterface $moduleManager);

	/**
	 * Extract links from registered services
	 * 
	 * @return array
	 */
	public function collect();

	/**
	 * Adds a service
	 * 
	 * @param MenuAwareManager $service Any manager that implements this interface
	 * @return \Menu\Service\LinkBuilder
	 */
	public function addService($module, MenuAwareManager $service);

	/**
	 * Adds service collection
	 * 
	 * @param array $services
	 * @return \Menu\Service\LinkBuilder
	 */
	public function addServices(array $services);
}
