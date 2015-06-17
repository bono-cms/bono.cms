<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm;

use Cms\AbstractCmsModule;
use MailForm\Service\FormManager;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getRoutes()
	{
		return include(__DIR__ . '/Config/routes.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfigData()
	{
		return include(__DIR__ . '/Config/module.config.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslations($language)
	{
		return $this->loadArray(__DIR__.'/Translations/'.$language.'/messages.php');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		$formMapper = $this->getMapper('/MailForm/Storage/MySQL/FormMapper');

		return array(
			'formManager' => new FormManager($formMapper, $this->getWebPageManager(), $this->getHistoryManager(), $this->getMailer(), $this->getMenuWidget())
		);
	}
}
