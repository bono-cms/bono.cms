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
use Krystal\Text\SlugGenerator;
use Krystal\Config\File\FileArray;
use Cms\Service\LanguageManager;
use Cms\Service\UserManager;
use Cms\Service\HistoryManager;
use Cms\Service\NotepadManager;
use Cms\Service\Mode;
use Cms\Service\NotificationManager;
use Cms\Service\WebPageManager;
use Cms\Service\ConfigManager;
use Cms\Service\Mailer;

final class Module extends AbstractModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getRoutes()
	{
		return array_merge(
			include(__DIR__ . '/Config/routes.install.php'),
			include(__DIR__ . '/Config/routes.php')
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslations($language)
	{
		return array_merge(
			$this->loadArray(__DIR__ . '/Translations/'.$language.'/messages.php'),
			$this->loadArray(__DIR__ . '/Translations/'.$language.'/validation.messages.php')
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfigData()
	{
		return array(
			'version' => '1.1'
		);
	}

	/**
	 * Returns mode service
	 * 
	 * @return \Admin\Service\Mode
	 */
	private function getModeService()
	{
		$mode = new Mode($this->getServiceLocator()->get('sessionBag'));
		$mode->prepare();

		return $mode;
	}

	/**
	 * Returns prepared configuration handler
	 * 
	 * @return \Admin\Service\ConfigManager
	 */
	private function getConfigService()
	{
		$adapter = new FileArray(__DIR__ . '/Config/module.config.php');
		$adapter->load();
		
		return new ConfigManager($adapter);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		// Cookie storage
		$storage = $this->getServiceLocator()->get('request')->getCookieBag();

		$mapperFactory = $this->getServiceLocator()->get('mapperFactory');
		$authManager = $this->getServiceLocator()->get('authManager');

		$config = $this->getConfigService();

		// Language
		$languageMapper = $mapperFactory->build('/Cms/Storage/MySQL/LanguageMapper');
		$languageManager = new LanguageManager($languageMapper, $config, $storage);

		$userMapper = $mapperFactory->build('/Cms/Storage/MySQL/UserMapper');

		$historyMapper = $mapperFactory->build('/Cms/Storage/MySQL/HistoryMapper');
		$historyMapper->setLangId($languageManager->getCurrentId());

		$notificationMapper = $mapperFactory->build('/Cms/Storage/MySQL/NotificationMapper');
		$notificationMapper->setLangId($languageManager->getCurrentId());
		$notepadMapper = $mapperFactory->build('/Cms/Storage/MySQL/NotepadMapper');

		$webPageMapper = $mapperFactory->build('/Cms/Storage/MySQL/WebPageMapper');
		$webPageMapper->setLangId($languageManager->getCurrentId());

		$webPageManager = new WebPageManager($webPageMapper, $languageMapper, new SlugGenerator());
		$notificationManager = new NotificationManager($notificationMapper);

		$userManager = new UserManager($userMapper, $authManager);
		$authManager->setAuthService($userManager);

		return array(

			'mailer' => new Mailer($notificationManager, $config->getEntity()),
			'configManager' => $config,
			'webPageManager' => $webPageManager,
			'notepadManager' => new NotepadManager($notepadMapper, 1),
			'mode' => $this->getModeService(),
			'userManager'	 => $userManager,
			'historyManager' => new HistoryManager($historyMapper),
			'notificationManager' => $notificationManager,
			'languageManager' => $languageManager
		);
	}
}
