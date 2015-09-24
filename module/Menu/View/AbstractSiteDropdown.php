<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\View;

use Krystal\Tree\AdjacencyList\Render\AbstractDropdown;
use Cms\Service\WebPageManagerInterface;
use Menu\Contract\WebPageAwareRendererInterface;

abstract class AbstractSiteDropdown extends AbstractDropdown implements WebPageAwareRendererInterface
{
	/**
	 * Web page manager to generate URLs using web page ids
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	protected $webPageManager;

	/**
	 * Web page id of the home page
	 * 
	 * @var string
	 */
	protected $homeWebPageId;

	/**
	 * Sets web page manager that can generate URLs
	 * 
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return void
	 */
	public function setWebPageManager(WebPageManagerInterface $webPageManager)
	{
		$this->webPageManager = $webPageManager;
	}

	/**
	 * Sets home web page id
	 * 
	 * @param string $homeWebPageId
	 * @return string
	 */
	public function setHomeWebPageId($homeWebPageId)
	{
		$this->homeWebPageId = $homeWebPageId;
	}

	/**
	 * Makes a full URL to item row
	 * 
	 * @param array $row
	 * @return string
	 */
	final protected function makeUrl(array $row)
	{
		// Special case to not generate home page's url
		if ($row['web_page_id'] == $this->homeWebPageId) {
			return '/';
		}

		if ((bool) $row['has_link']) {
			return $row['link'];
		} else {
			return $this->webPageManager->generate($row['web_page_id']);
		}
	}
}
