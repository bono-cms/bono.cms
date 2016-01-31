<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Notepad extends AbstractController
{
    /**
     * Returns notepad manager
     * 
     * @return \Cms\Service\NotepadManager
     */
    private function getNotepadManager()
    {
        return $this->getService('Cms', 'notepadManager');
    }

    /**
     * Renders a notepad
     * 
     * @return string
     */
    public function indexAction()
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName())
                   ->appendScript('@Cms/admin/notepad.js');

        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Notepad');

        return $this->view->render('notepad', array(
            'content' => $this->getNotepadManager()->fetch(),
            'title' => 'Notepad'
        ));
    }

    /**
     * Saves notepad's data
     * 
     * @return string
     */
    public function saveAction()
    {
        $content = $this->request->getPost('notepad');

        if ($this->getNotepadManager()->store($content)) {
            $this->flashBag->set('success', 'Notepad has been updated successfully');
            return '1';
        }
    }
}
