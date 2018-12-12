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
use Krystal\Http\FileTransfer\FileEntityInterface;

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
     * @param array $files Files to be sent if present
     * @return boolean
     */
    private function sendMessage($to, $subject, $body, array $files = array())
    {
        // If we have SMTP transport turned on, then we'd use appropriate Swift's transport
        if ($this->config->getUseSmtpDriver() != true) {
            $from = $this->config->getSmtpUsername();
            // SMTP transport
            $transport = \Swift_SmtpTransport::newInstance($this->config->getSmtpHost(), $this->config->getSmtpPort(), $this->config->getSmtpSecureLayer())
                                          ->setUsername($from)
                                          ->setPassword($this->config->getSmtpPassword());
        } else {
            $from = array('no-reply@'.$this->config->getDomain());
            $transport = \Swift_MailTransport::newInstance(null);
        }

        // Create the Mailer using your created Transport
        $mailer = \Swift_Mailer::newInstance($transport);

        // Build Swift's message
        $message = \Swift_Message::newInstance($subject)
                              ->setFrom($from)
                              ->setContentType('text/html')
                              ->setTo($to)
                              ->setBody($body);

        // if files provided, then attach them
        if (!empty($files)) {
            foreach ($files as $name => $file) {
                if ($file instanceof FileEntityInterface) {
                    $path = \Swift_Attachment::fromPath($file->getTmpName())->setFilename($file->getName());
                } else {
                    $path = \Swift_Attachment::fromPath($file);

                    if (!is_numeric($name)) {
                        $path->setFilename($name);
                    }
                }

                $message->attach($path);
            }
        }

        // Re-define the id
        //$msgId = $message->getHeaders()->get('Message-ID');
        //$msgId->setId(time().'.'.uniqid('token').'@'.$this->config->getDomain());

        return $mailer->send($message, $failed) != 0;
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
    public function send($subject, $body, $notification = null, array $files = array())
    {
        if ($notification === null) {
            $notification = 'You have received a new message';
        }

        if ($this->sendMessage(array($this->config->getNotificationEmail()), $subject, $body, $files)) {
            $this->notificationManager->notify($notification);
            return true;

        } else {
            return false;
        }
    }
}
