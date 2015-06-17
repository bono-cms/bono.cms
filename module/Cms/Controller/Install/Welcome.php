<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Install;

final class Welcome extends AbstractController
{
	/**
	 * Index command
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		return $this->view->render('welcome', array(
			'title' => 'Welcome'
		));
	}

	/**
	 * Install a system
	 * 
	 * @return string
	 */
	public function installAction()
	{
		if ($this->request->isPost()) {
			
		}
	}
}
