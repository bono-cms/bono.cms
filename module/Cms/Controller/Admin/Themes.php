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

use Cms\Service\ThemeService;

final class Themes extends AbstractController
{
    /**
     * Creates theme service instance
     * 
     * @return \Cms\Service\ThemeService
     */
    private function createThemeService()
    {
        return new ThemeService($this->appConfig);
    }

    /**
     * List all available themes
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->view->getBreadcrumbBag()->addOne('Themes');

        return $this->view->render('themes', array(
            'themes' => $this->createThemeService()->getThemes(),
            'current' => $this->config->get('Cms', 'theme')
        ));
    }

    /**
     * Save changes
     * 
     * @return string
     */
    public function tweakAction()
    {
        $current = $this->request->getPost('current');

        // Save incoming value in configuration
        $this->config->store('Cms', 'theme', $current);

        // Update view
        $this->flashBag->set('success', 'Selected theme has been set as default');
        return '1';
    }

    /**
     * Deletes a theme by its base folder name
     * 
     * @param string $theme Base folder name
     * @return string
     */
    public function deleteAction($theme)
    {
        if ($this->createThemeService()->dropTheme($theme)) {
            $this->flashBag->set('success', 'Selected theme has been removed successfully');
        } else {
            $this->flashBag->set('warning', 'An error occured during theme removal');
        }

        return '1';
    }

    /**
     * Drop a theme by its folder name
     * 
     * @return string
     */
    public function deleteManyAction()
    {
        if ($this->request->hasPost('toDelete')) {
            // Grab theme names to be removed
            $themes = array_keys($this->request->getPost('toDelete'));

            if ($this->createThemeService()->dropThemes($themes)) {
                $this->flashBag->set('success', 'Selected themes have been removed successfully');
            }

        } else {
            $this->flashBag->set('warning', 'Select at least one theme to remove');
        }

        return '1';
    }
}
