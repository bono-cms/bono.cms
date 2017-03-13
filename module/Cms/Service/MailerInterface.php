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

interface MailerInterface
{
    /**
     * Sends a message to the provided email
     * 
     * @param string $email Target email
     * @param string $subject Email subject
     * @param string $body
     * @return boolean
     */
    public function sendTo($email, $subject, $body);

    /**
     * Sends a message to administrator
     * 
     * @param string Message's subject
     * @param string $body Body to be sent
     * @param string $notification Default notification message to be pop in administration panel
     * @return boolean Depending on success
     */
    public function send($subject, $body, $notification = 'You have received a new message');
}
