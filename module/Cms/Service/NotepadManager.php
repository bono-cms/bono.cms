<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

use Cms\Service\AbstractManager;
use Cms\Storage\NotepadMapperInterface;

final class NotepadManager extends AbstractManager implements NotepadManagerInterface
{
	/**
	 * Any compliant notepad's mapper
	 * 
	 * @var \Cms\Storage\NotepadMapperInterface
	 */
	private $notepadMapper;

	/**
	 * Target user id we're working with
	 * 
	 * @var string
	 */
	private $userId;

	/**
	 * State initialization
	 * 
	 * @param \Cms\Storage\NotepadMapperInterface $notepadMapper
	 * @return void
	 */
	public function __construct(NotepadMapperInterface $notepadMapper)
	{
		$this->notepadMapper = $notepadMapper;
	}

	/**
	 * Defines id of current logged in user
	 * 
	 * @param string $userId
	 * @return void
	 */
	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	/**
	 * Checks whether content exists with provided user's id
	 * 
	 * @return boolean
	 */
	private function exists()
	{
		return $this->notepadMapper->exists($this->userId);
	}

	/**
	 * Stores notepad's data
	 * 
	 * @param string $content
	 * @return void
	 */
	public function store($content)
	{
		if ($this->exists()) {
			return $this->notepadMapper->update($this->userId, $content);
		} else {
			return $this->notepadMapper->insert($this->userId, $content);
		}
	}

	/**
	 * Returns notepad's content
	 * 
	 * @return string
	 */
	public function fetch()
	{
		if ($this->exists()) {
			return $this->notepadMapper->fetchByUserId($this->userId);
		} else {
			return '';
		}
	}
}
