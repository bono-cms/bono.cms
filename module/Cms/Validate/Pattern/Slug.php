<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Validate\Pattern;

use Cms\Service\WebPageManagerInterface;

final class Slug
{
	/**
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * State initialization
	 * 
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function __construct(WebPageManagerInterface $webPageManager)
	{
		$this->webPageManager = $webPageManager;
		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDefinition()
	{
		return $this->getWithDefaults(array(
			'required' => true,
			'rules' => array(
				'NotEmpty' => array(
					'message' => 'CAPTCHA can not be blank'
				),
				'Boolean' => array(
					'value' => $this->captcha
				)
			)
		
		));
	}
}
