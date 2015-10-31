<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

use Krystal\Config\File\AbstractConfigManager;
use Krystal\Security\Filter;

final class ConfigManager extends AbstractConfigManager
{
    /**
     * {@inheritDoc}
     */
    protected function populate()
    {
        $entity = $this->getEntity();

        // SMTP configuration
        $entity->setSmtpPassword(Filter::escape($this->get('smtp_password')))
               ->setSmtpHost(Filter::escape($this->get('smtp_host')))
               ->setSmtpUsername(Filter::escape($this->get('smtp_username')))
               ->setSmtpSecureLayer(Filter::escape($this->get('smtp_secure_layer')))
               ->setSmtpPort(Filter::escape($this->get('smtp_port')))
               ->setUseSmtpDriver((bool)($this->get('use_smtp_driver')))
               ->setDomain($this->get('domain', $_SERVER['HTTP_HOST']));

        // SMTP Secure layers
        $entity->setSmtpSecureLayers(array(
            '0'   => 'None',
            'ssl' => 'SSL',
            'tls' => 'TLS'
        ));

        // The rest
        $entity->setNotificationEmail(Filter::escape($this->get('notification_email')))
               ->setKeepTrack((bool) ($this->get('keep_track')))
               ->setSiteEnabled((bool) ($this->get('site_enabled')))
               // No need to escape this
               ->setSiteDownReason(($this->get('site_down_reason')));
    }
}
