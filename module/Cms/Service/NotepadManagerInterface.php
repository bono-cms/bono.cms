<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

interface NotepadManagerInterface
{
    /**
     * Defines id of current logged in user
     * 
     * @param string $userId
     * @return void
     */
    public function setUserId($userId);

    /**
     * Stores notepad's data
     * 
     * @param string $content
     * @return void
     */
    public function store($content);

    /**
     * Returns notepad's content
     * 
     * @return string
     */
    public function fetch();
}
