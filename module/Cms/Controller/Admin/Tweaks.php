<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Krystal\Validation\Validator;

final class Tweaks extends AbstractConfigController
{
    /**
     * {@inheritDoc}
     */
    protected function configureValidator(Validator $validator)
    {
        $validator->field('config.notification_email', 'Notification Email')
                  ->required()
                  ->addRule('email');
    }

    /**
     * {@inheritDoc}
     */
    protected function loadPlugins()
    {
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName())
                   ->appendScript('@Cms/admin/config.js');

        $this->view->getBreadcrumbBag()
                   ->addOne('Tweaks');
    }
}
