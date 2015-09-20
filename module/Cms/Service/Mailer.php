<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

use Krystal\Stdlib\VirtualEntity;

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
	 * Loads mailer library
	 * 
	 * @return void
	 */
	private function loadLibrary()
	{
		// Yeah, obviously that's not the best way to include the library
		require(dirname(dirname(dirname(__DIR__)))) . '/vendor/SwiftMailer/swift_required.php';
	}

	/**
	 * Sends a mail
	 * 
	 * @param string $to Delivery e-mail address
	 * @param string Message's subject
	 * @param string $text Data to be sent
	 * @param string $notification Default notification message to be pop in administration panel
	 * @return boolean Depending on success
	 */
	public function send($subject, $text, $notification = 'You have received a new message')
	{
		$this->loadLibrary();

		// If we have SMTP transport turned on, then we'd use appropriate Swift's transport
		if ($this->config->getUseSmtpDriver() != true) {
			// SMTP transport
			$transport = \Swift_SmtpTransport::newInstance($this->config->getSmtpHost(), $this->config->getSmtpPort(), $this->config->getSmtpSecureLayer())
										  ->setUsername($this->config->getSmtpUsername())
										  ->setPassword($this->config->getSmtpPassword());
		} else {
			$transport = \Swift_MailTransport::newInstance(null);
		}

		// Create the Mailer using your created Transport
		$mailer = \Swift_Mailer::newInstance($transport);

		// Build Swift's message
		$message = \Swift_Message::newInstance($subject)
							  ->setFrom(array('no-reply@'.$this->config->getDomain()))
							  ->setContentType('text/html')
							  ->setTo(array($this->config->getNotificationEmail()))
							  ->setBody($text);
		// Re-define the id
		$msgId = $message->getHeaders()->get('Message-ID');
		$msgId->setId(time().'.'.uniqid('token').'@'.$this->config->getDomain());

		if ($mailer->send($message, $failed) != 0) {
			$this->notificationManager->notify($notification);
			return true;

		} else {
			return false;
		}
	}
}
