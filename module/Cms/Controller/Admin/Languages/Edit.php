<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin\Languages;

final class Edit extends AbstractLanguage
{
    /**
     * Shows edit form
     * 
     * @param string $id Language id
     * @return string
     */
    public function indexAction($id)
    {
        $language = $this->getLanguageManager()->fetchById($id);

        if ($language !== false) {
            $this->loadSharedPlugins();
            $this->loadBreadcrumbs('Edit the language');

            return $this->view->render($this->getTemplatePath(), array(
                'countries' => $this->getLanguageManager()->getCountries(),
                'title' => 'Edit the language',
                'language' => $language
            ));

        } else {
            return false;
        }
    }

    /**
     * Updates a language
     * 
     * @return string
     */
    public function updateAction()
    {
        $formValidator = $this->getValidator($this->request->getPost('language'));

        if ($formValidator->isValid()) {
            if ($this->getLanguageManager()->update($this->request->getPost('language'))) {
                $this->flashBag->set('success', 'The language has been updated successfully');
                return '1';
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
