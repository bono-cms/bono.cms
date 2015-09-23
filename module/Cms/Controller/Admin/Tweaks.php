<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Krystal\Validate\Pattern;

final class Tweaks extends AbstractConfigController
{
	/**
	 * {@inheritDoc}
	 */
	protected function getValidationRules()
	{
		return array(
			'notification_email' => new Pattern\Email()
		);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->load($this->getWysiwygPluginName())
				   ->appendScript($this->getWithAssetPath('/admin/config.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Tweaks'
			)
		));
	}
}
