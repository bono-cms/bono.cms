<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace QA;

use Cms\AbstractCmsModule;
Use QA\Service\QaManager;

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
	public function getTranslations($language)
	{
		return $this->loadArray(__DIR__ . '/Translations/'.$language.'/messages.php');
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
	public function getServiceProviders()
	{
		return array(
			'qaManager' => new QaManager($this->getMapper('/QA/Storage/MySQL/QaMapper'), $this->getHistoryManager())
		);
	}
}
