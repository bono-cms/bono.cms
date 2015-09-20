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

interface MailerInterface
{
	/**
	 * Sends a mail
	 * 
	 * @param string Message's subject
	 * @param string $text Data to be sent
	 * @param string $notification Default notification message to be pop in administration panel
	 * @return boolean Depending on success
	 */
	public function send($subject, $text, $notification = 'You have received a new message');
}
