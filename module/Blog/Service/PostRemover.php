<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Service;

use Blog\Storage\PostMapperInterface;
use Cms\Service\WebPageManagerInterface;

/* Internal service to remove posts */
final class PostRemover implements PostRemoverInterface
{
	/**
	 * Any-compliant post mapper
	 * 
	 * @var \Blog\Storage\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * Web page manager is used to remove post web pages
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * State initialization
	 * 
	 * @param \Blog\Storage\PostMapperInterface $postMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function __construct(PostMapperInterface $postMapper, WebPageManagerInterface $webPageManager)
	{
		$this->postMapper = $postMapper;
		$this->webPageManager = $webPageManager;
	}

	/**
	 * Removes a web page by post's associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	private function removeWebPageById($id)
	{
		$webPageId = $this->postMapper->fetchWebPageIdById($id);
		return $this->webPageManager->deleteById($webPageId);
	}

	/**
	 * Removes all data by post's associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	public function removeAllById($id)
	{
		$this->removeWebPageById($id);
		$this->postMapper->deleteById($id);

		return true;
	}
}
