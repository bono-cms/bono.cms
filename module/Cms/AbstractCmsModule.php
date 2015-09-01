<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms;

use Krystal\Application\Module\AbstractModule;
use Krystal\Config\File\FileArray;
use RuntimeException;

/**
 * One module with shortcut methods for all CMS modules
 * All CMS modules should inherit from this base module
 * 
 * Most of the protected methods are marked as final, because it adheres to Open-Closed Principle
 */
abstract class AbstractCmsModule extends AbstractModule
{
	/**
	 * Configuration service
	 * 
	 * @var \Krystal\Config\AbstractConfigManager
	 */
	protected $configService;

	/**
	 * Returns configuration service of descendant's module
	 * 
	 * @return \Krystal\Config\AbstractConfigManager
	 */
	final protected function getConfigService()
	{
		// Lazy initialization
		if (is_null($this->configService)) {

			// Build qualified manager's class name
			$manager = sprintf('\%s\Service\ConfigManager', $this->getCurrentModuleName());

			// Make sure the manager class exists, before processing
			if (!class_exists($manager)) {
				throw new RuntimeException(sprintf('Module %s does not have ConfigManager service', $this->getCurrentModuleName()));
			}

			$adapter = new FileArray($this->getPathProvider()->getWithConfigDir('module.config.php'));
			$adapter->load();

			$this->configService = new $manager($adapter);
		}

		return $this->configService;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRoutes()
	{
		return include($this->getPathProvider()->getWithConfigDir('routes.php'));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfigData()
	{
		return include($this->getPathProvider()->getWithConfigDir('module.config.php'));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslations($language)
	{
		return $this->loadArray($this->getPathProvider()->getWithTranslationsDir($language, 'messages.php'));
	}

	/**
	 * Returns administration module
	 * 
	 * @return \Cms\Module
	 */
	final protected function getCmsModule()
	{
		return $this->moduleManager->getModule('Cms');
	}

	/**
	 * Returns mailer
	 * 
	 * @return \Cms\Service\Mailer
	 */
	final protected function getMailer()
	{
		return $this->getCmsModule()->getService('mailer');
	}

	/**
	 * Returns web page manager
	 * 
	 * @return \Cms\Service\WebPageManager
	 */
	final protected function getWebPageManager()
	{
		return $this->getCmsModule()->getService('webPageManager');
	}

	/**
	 * Returns history manager
	 * 
	 * @return \Cms\Service\HistoryManager
	 */
	final protected function getHistoryManager()
	{
		return $this->getCmsModule()->getService('historyManager');
	}

	/**
	 * Returns notification manager
	 * 
	 * @return \Cms\Service\NotificationManager
	 */
	final protected function getNotificationManager()
	{
		return $this->getCmsModule()->getService('notificationManager');
	}

	/**
	 * Returns menu widget
	 * 
	 * @return \Menu\Service\MenuWidget
	 */
	final protected function getMenuWidget()
	{
		return $this->moduleManager->getModule('Menu')->getService('menuWidget');
	}

	/**
	 * Builds an instance of a mapper
	 * 
	 * @param string $namespace
	 * @return @TODO
	 */
	final protected function getMapper($namespace, $withLang = true)
	{
		$mapperFactory = $this->getServiceLocator()->get('mapperFactory');

		$mapper = $mapperFactory->build($namespace);

		if ($withLang && method_exists($mapper, 'setLangId')) {
			$languageManager = $this->getCmsModule()->getService('languageManager');
			$mapper->setLangId($languageManager->getCurrentId());
		}
		
		return $mapper;
	}
}
