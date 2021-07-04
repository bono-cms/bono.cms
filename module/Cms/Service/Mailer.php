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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // If we have SMTP transport turned on, then we'd use appropriate Swift's transport
        if ($this->config->getUseSmtpDriver() != true) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $this->config->getSmtpHost();           //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $this->config->getSmtpUsername();       //SMTP username
            $mail->Password   = $this->config->getSmtpPassword();       //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = $this->config->getSmtpPort();           //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        } else {
            $mail->setFrom('no-reply@'.$this->config->getDomain());
        }

        // if files provided, then attach them
        if (!empty($files)) {
            foreach ($files as $name => $file) {
                if ($file instanceof FileEntityInterface) {
                    $mail->addAttachment($file->getTmpName(), $file->getName());
                } else {
                    $mail->addAttachment($file);
                }
            }
        }

        $mail->isHTML(true);

        if (is_array($to)) {
            foreach ($to as $receiver) {
                $mail->addAddress($receiver);
            }
        } else {
            $mail->addAddress($to);
        }

        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
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
