<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Service;

final class SiteService implements SiteServiceInterface
{
	/**
	 * Post manager
	 * 
	 * @var \News\Service\PostManagerInterface
	 */
	private $postManager;

	/**
	 * State initialization
	 * 
	 * @param \News\Service\PostManagerInterface $postManager
	 * @return void
	 */
	public function __construct(PostManagerInterface $postManager)
	{
		$this->postManager = $postManager;
	}

	/**
	 * Returns random posts
	 * 
	 * @param integer $amount
	 * @param string $categoryId Optionally can be filtered by category id
	 * @return array
	 */
	public function getRandom($amount, $categoryId = null)
	{
		return $this->postManager->fetchRandomPublished($amount, $categoryId);
	}
}
