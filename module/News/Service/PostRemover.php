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

use News\Storage\PostMapperInterface;
use Krystal\Image\Tool\ImageManagerInterface;
use Cms\Service\WebPageManagerInterface;

final class PostRemover implements PostRemoverInterface
{
	/**
	 * Image manager to remove post's images
	 * 
	 * @var \Krystal\Image\Tool\ImageManagerInterface
	 */
	private $imageManager;

	/**
	 * Any compliant post mapper
	 * 
	 * @var \News\Storage\PostMapperInterface
	 */
	private $postMapper;

	/**
	 * Web page manager to remove post's associated web pages
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * State initialization
	 * 
	 * @param \News\Storage\PostMapperInterface $postMapper
	 * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function __construct(PostMapperInterface $postMapper, ImageManagerInterface $imageManager, WebPageManagerInterface $webPageManager)
	{
		$this->postMapper = $postMapper;
		$this->imageManager = $imageManager;
		$this->webPageManager = $webPageManager;
	}

	/**
	 * Completely removes a post by its associated id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	public function removeAllById($id)
	{
		// Order of execution is important
		$this->removeWebPageById($id);
		$this->removeImagesById($id);

		return $this->postMapper->deleteById($id);
	}

	/**
	 * Removes all images by associated post id
	 * 
	 * @param string $id Post's id
	 * @return boolean
	 */
	private function removeImagesById($id)
	{
		return $this->imageManager->delete($id);
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
}
