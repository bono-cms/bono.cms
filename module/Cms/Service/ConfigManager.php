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

use Krystal\Config\ConfigModuleService;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;

final class ConfigManager extends ConfigModuleService
{
    /**
     * {@inheritDoc}
     */
    public function getEntity()
    {
        $entity = new VirtualEntity();

        // SMTP configuration
        $entity->setSmtpPassword($this->get('smtp_password'), VirtualEntity::FILTER_TAGS)
               ->setSmtpHost($this->get('smtp_host'), VirtualEntity::FILTER_TAGS)
               ->setSmtpUsername($this->get('smtp_username'), VirtualEntity::FILTER_TAGS)
               ->setSmtpSecureLayer($this->get('smtp_secure_layer'), VirtualEntity::FILTER_TAGS)
               ->setSmtpPort($this->get('smtp_port'), VirtualEntity::FILTER_INT)
               ->setUseSmtpDriver($this->get('use_smtp_driver', true), VirtualEntity::FILTER_BOOL)
               ->setDomain($this->get('domain', $_SERVER['HTTP_HOST']), VirtualEntity::FILTER_TAGS);

        // SMTP Secure layers
        $entity->setSmtpSecureLayers(array(
            '0'   => 'None',
            'ssl' => 'SSL',
            'tls' => 'TLS'
        ));

        // The rest
        $entity->setNotificationEmail($this->get('notification_email'), VirtualEntity::FILTER_TAGS)
               ->setKeepTrack($this->get('keep_track', true), VirtualEntity::FILTER_BOOL)
               ->setSiteEnabled($this->get('site_enabled'), VirtualEntity::FILTER_BOOL)
               // No need to escape this
               ->setSiteDownReason($this->get('site_down_reason'), VirtualEntity::FILTER_SAFE_TAGS)
               ->setInstalled($this->get('installed'), VirtualEntity::FILTER_BOOL);

        return $entity;
    }
}
