<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

use Krystal\Stdlib\VirtualEntity;

final class TimeBagFactory
{
	/**
	 * Configuration entity
	 * 
	 * @var \Krystal\Stdlib\VirtualEntity
	 */
	private $config;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $config
	 * @return void
	 */
	public function __construct(VirtualEntity $config)
	{
		$this->config = $config;
	}

	/**
	 * Builds time bag
	 * 
	 * @return \News\Service\TimeBag
	 */
	public function build()
	{
		// With defaults
		$listFormat = $this->config->getTimeFormatInList();
		$postFormat = $this->config->getTimeFormatInPost();
		$announceFormat = $listFormat;
		$panelFormat = 'm/d/Y';

		return new TimeBag($listFormat, $postFormat, $announceFormat, $panelFormat);
	}
}
