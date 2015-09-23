<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Service;

final class SiteService implements SiteServiceInterface
{
	/**
	 * Advice manager service
	 * 
	 * @var \Advice\Service\AdviceManagerInterface
	 */
	private $adviceManager;

	/**
	 * State initialization
	 * 
	 * @param \Advice\Service\AdviceManagerInterface $adviceManager
	 * @return void
	 */
	public function __construct(AdviceManagerInterface $adviceManager)
	{
		$this->adviceManager = $adviceManager;
	}

	/**
	 * Returns random advice entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function getRandom()
	{
		return $this->adviceManager->fetchRandom();
	}

	/**
	 * Finds an advice by its associated id and returns its entity
	 * 
	 * @param string $id
	 * @return \Krystal\Stdlib\VirtualEntity|boolean
	 */
	public function getById($id)
	{
		return $this->adviceManager->fetchById($id);
	}
}
