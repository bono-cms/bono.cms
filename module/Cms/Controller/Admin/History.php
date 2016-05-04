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

final class History extends AbstractController
{
    /**
     * Shows history grid
     * 
     * @param integer $page Current page
     * @return string
     */
    public function indexAction($page = 1)
    {
        $this->view->getBreadcrumbBag()
                   ->addOne('History');

        $historyManager = $this->getService('Cms', 'historyManager');
        $userManager = $this->getService('Cms', 'userManager');

        $paginator = $historyManager->getPaginator();
        $paginator->setUrl($this->createUrl('Cms:Admin:History@indexAction', array(), 1));

        return $this->view->render('history', array(
            'title' => 'History',
            'paginator' => $historyManager->getPaginator(),
            'records'   => $historyManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
            'userManager' => $userManager
        ));
    }

    /**
     * Clears all history
     * 
     * @return string
     */
    public function clearAction()
    {
        $historyManager = $this->getService('Cms', 'historyManager');

        if ($historyManager->clear()) {
            $this->flashBag->set('success', 'History has been cleared successfully');
            return '1';
        }
    }
}
