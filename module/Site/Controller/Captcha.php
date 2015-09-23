<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Site\Controller;

use Krystal\Application\Controller\AbstractController;

final class Captcha extends AbstractController
{
	/**
	 * Renders the CAPTCHA
	 * 
	 * @return void
	 */
	public function renderAction()
	{
		$this->captcha->render();
	}
}
