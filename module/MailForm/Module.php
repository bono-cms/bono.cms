<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
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
	public function getServiceProviders()
	{
		$formMapper = $this->getMapper('/MailForm/Storage/MySQL/FormMapper');

		return array(
			'formManager' => new FormManager($formMapper, $this->getWebPageManager(), $this->getHistoryManager(), $this->getMenuWidget())
		);
	}
}
