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

use Krystal\Stdlib\VirtualEntity;
use Krystal\Mail\Mailer as FrameworkMailer;

final class Mailer implements MailerInterface
{
    /**
     * Configuration entity
     * 
     * @var \Krystal\Stdlib\VirtualEntity
     */
    private $config;

    /**
     * Notification manager to track sending failures
     * 
     * @var \Cms\Service\NotificationManagerInterface
     */
    private $notificationManager;

    /**
     * State initialization
     * 
     * @param \Cms\Service\NotificationManagerInterface
     * @param \Krystal\Stdlib\VirtualEntity $config
     * @return void
     */
    public function __construct(NotificationManagerInterface $notificationManager, VirtualEntity $config)
    {
        $this->notificationManager = $notificationManager;
        $this->config = $config;
    }

    /**
     * Sends a message. For internal usage only
     * 
     * @param string|array $to
     * @param string $subject
     * @param string $body
     * @param array $files Optional files
     * @return boolean
     */
    private function sendMessage($to, $subject, $body, array $files = array())
    {
        $mailer = new FrameworkMailer([
            'from' => $this->config->getSmtpUsername(),
            'smtp' => [
                'enabled' => $this->config->getUseSmtpDriver() != true,
                'host' => $this->config->getSmtpHost(),
                'username' => $this->config->getSmtpUsername(),
                'password' => $this->config->getSmtpPassword(),
                'port' => $this->config->getSmtpPort(),
                'protocol' => $this->config->getSmtpSecureLayer()
            ]
        ]);

        return $mailer->send($to, $subject, $body, $files);
    }

    /**
     * Sends a message to the provided email
     * 
     * @param string $email Target email
     * @param string $subject Email subject
     * @param string $body
     * @param array $files Files to be sent if present
     * @return boolean
     */
    public function sendTo($email, $subject, $body, array $files = array())
    {
        return $this->sendMessage(array($email), $subject, $body, $files);
    }

    /**
     * Sends a message to administrator
     * 
     * @param string Message's subject
     * @param string $body Body to be sent
     * @param string $notification Default notification message to be pop in administration panel
     * @param array $files Files to be sent if present
     * @return boolean Depending on success
     */
    public function send($subject, $body, $notification = 'You have received a new message', array $files = array())
    {
        if ($this->sendMessage(array($this->config->getNotificationEmail()), $subject, $body, $files)) {
            $this->notificationManager->notify($notification);
            return true;
        } else {
            return false;
        }
    }
}
