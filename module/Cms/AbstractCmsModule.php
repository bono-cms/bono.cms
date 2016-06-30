<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms;

use Krystal\Application\Module\AbstractModule;

/**
 * One module with shortcut methods for all CMS modules
 * All CMS modules should inherit from this base module
 * 
 * Most of the protected methods are marked as final, because it adheres to Open-Closed Principle
 */
abstract class AbstractCmsModule extends AbstractModule
{
    /**
     * Creates configuration entity object
     * 
     * @return \Krystal\Stdlib\VirtualEntity
     */
    final protected function createConfigService()
    {
        // Build qualified manager's class name
        $ns = sprintf('\%s\Service\ConfigManager', $this->getCurrentModuleName());

        // Make sure the manager class exists, before processing
        if (!class_exists($ns)) {
            throw new RuntimeException(sprintf('Module %s does not have ConfigManager service', $this->getCurrentModuleName()));
        }

        return new $ns($this->getCurrentModuleName(), $this->getServiceLocator()->get('config'));
    }

    /**
     * Creates dynamic routes
     * 
     * @param array $routes
     * @param string $segment
     * @return array
     */
    private function createDynamicRoutes(array $routes, $segment)
    {
        $result = array();

        foreach ($routes as $key => $value) {
            if (strpos($key, '%s') !== false) {
                $key = sprintf($key, $segment);
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Returns configuration entity
     * 
     * @return \Krystal\Stdlib\VirtualEntity
     */
    final protected function getConfigEntity()
    {
        return $this->getConfigService()->getEntity();
    }

    /**
     * {@inheritDoc}
     */
    public function getRoutes()
    {
        $segment = $this->getServiceLocator()->get('paramBag')->get('admin_segment');
        
        $routes = include($this->getPathProvider()->getWithConfigDir('routes.php'));
        $routes = $this->createDynamicRoutes($routes, $segment);

        return $routes;
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
