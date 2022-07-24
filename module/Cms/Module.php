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

use Krystal\Text\SlugGenerator;
use Cms\Service\LanguageManager;
use Cms\Service\UserManager;
use Cms\Service\HistoryManager;
use Cms\Service\Mode;
use Cms\Service\NotificationManager;
use Cms\Service\WebPageManager;
use Cms\Service\ConfigManager;
use Cms\Service\Mailer;

final class Module extends AbstractCmsModule
{
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
     * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        $baseUrl = $this->getServiceLocator()->get('request')->getBaseUrl();

        // Cookie storage
        $storage = $this->getServiceLocator()->get('request')->getCookieBag();

        $mapperFactory = $this->getServiceLocator()->get('mapperFactory');
        $authManager = $this->getServiceLocator()->get('authManager');

        $config = $this->createConfigService();

        // Language
        $languageMapper = $mapperFactory->build('/Cms/Storage/MySQL/LanguageMapper');
        $languageManager = new LanguageManager($languageMapper, $config, $storage);

        $userMapper = $mapperFactory->build('/Cms/Storage/MySQL/UserMapper');

        $historyMapper = $mapperFactory->build('/Cms/Storage/MySQL/HistoryMapper');
        $historyMapper->setLangId($languageManager->getCurrentId());

        $notificationMapper = $mapperFactory->build('/Cms/Storage/MySQL/NotificationMapper');
        $notificationMapper->setLangId($languageManager->getCurrentId());

        $webPageMapper = $mapperFactory->build('/Cms/Storage/MySQL/WebPageMapper');
        $webPageMapper->setLangId($languageManager->getCurrentId());

        $webPageManager = new WebPageManager($webPageMapper, $languageMapper, new SlugGenerator(), $baseUrl);
        $notificationManager = new NotificationManager($notificationMapper);

        $userManager = new UserManager($userMapper, $authManager);
        $authManager->setAuthService($userManager);

        return array(
            'mailer' => new Mailer($notificationManager, $config->getEntity()),
            'configManager' => $config,
            'webPageManager' => $webPageManager,
            'mode' => $this->getModeService(),
            'userManager'    => $userManager,
            'historyManager' => new HistoryManager($historyMapper),
            'notificationManager' => $notificationManager,
            'languageManager' => $languageManager
        );
    }
}
