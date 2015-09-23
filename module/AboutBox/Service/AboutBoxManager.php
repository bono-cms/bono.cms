<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace AboutBox\Service;

use AboutBox\Storage\AboutBoxMapperInterface;
use Cms\Service\HistoryManagerInterface;
use Krystal\Security\Filter;

final class AboutBoxManager implements AboutBoxManagerInterface
{
	/**
	 * Any compliant box's mapper
	 * 
	 * @var \AboutBox\Storage\AboutBoxMapperInterface
	 */
	private $aboutBoxMapper;

	/**
	 * History manager to keep tracks
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \AboutBox\Storage\AboutBoxMapperInterface $aboutBoxMapper
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @return void
	 */
	public function __construct(AboutBoxMapperInterface $aboutBoxMapper, HistoryManagerInterface $historyManager)
	{
		$this->aboutBoxMapper = $aboutBoxMapper;
		$this->historyManager = $historyManager;
	}

	/**
	 * Tracks activity
	 * 
	 * @param string $message Message to be tracked
	 * @return boolean
	 */
	private function track($message)
	{
		return $this->historyManager->write('AboutBox', $message, '');
	}

	/**
	 * Fetches box's content
	 * 
	 * @return string
	 */
	public function fetch()
	{
		return Filter::escapeContent($this->aboutBoxMapper->fetch());
	}

	/**
	 * Updates box's data
	 * 
	 * @param string $content New content
	 * @return boolean
	 */
	public function update($content)
	{
		$this->track('About box has been updated');

		if ($this->aboutBoxMapper->exists()) {
			return $this->aboutBoxMapper->update($content);
		} else {
			return $this->aboutBoxMapper->insert($content);
		}
	}
}
