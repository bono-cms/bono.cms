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

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractLanguage extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected function bootstrap()
    {
        $this->disableLanguageCheck();
        parent::bootstrap();
    }

    /**
     * Return configured validator's instance
     * 
     * @param array $post Raw post data
     * @return \Krystal\Validate\ValidatorChain
     */
    final protected function getValidator(array $post)
    {
        return $this->validatorFactory->build(array(
            'input' => array(
                'source' => $post,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'code' => new Pattern\LanguageCode(),
                    'order' => new Pattern\Order()
                )
            )
        ));
    }

    /**
     * Loads shared plugins
     * 
     * @return void
     */
    final protected function loadSharedPlugins()
    {
        $this->view->getPluginBag()
                   ->appendScript('@Cms/admin/language/language.form.js');
    }

    /**
     * Returns language manager
     * 
     * @return \Admin\Service\LanguageManager
     */
    final protected function getLanguageManager()
    {
        return $this->getService('Cms', 'languageManager');
    }

    /**
     * Loads breadcrumbs
     * 
     * @param string $title
     * @return void
     */
    final protected function loadBreadcrumbs($title)
    {
        $this->view->getBreadcrumbBag()->addOne('Languages', 'Cms:Admin:Languages:Browser@indexAction')
                                       ->addOne($title);
    }

    /**
     * Returns template path
     * 
     * @return string
     */
    final protected function getTemplatePath()
    {
        return 'languages/language.form';
    }
}
