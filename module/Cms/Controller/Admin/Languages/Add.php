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

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractLanguage
{
    /**
     * Shows adding form
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->loadSharedPlugins();
        $this->loadBreadcrumbs('Add a language');

        $language = new VirtualEntity();
        $language->setPublished(true)
                 ->setOrder(0);

        return $this->view->render($this->getTemplatePath(), array(
            'countries' => $this->getLanguageManager()->getCountries(),
            'title' => 'Add a language',
            'language' => $language
        ));
    }

    /**
     * Adds a language
     * 
     * @return string
     */
    public function addAction()
    {
        $formValidator = $this->getValidator($this->request->getPost('language'));

        if ($formValidator->isValid()) {
            $languageManager = $this->getLanguageManager();

            if ($languageManager->add($this->request->getPost('language'))) {
                $this->flashBag->set('success', 'A language has been added successfully');
                return $languageManager->getLastId();
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
