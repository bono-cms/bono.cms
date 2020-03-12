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

use Krystal\Application\Model\AbstractService;
use Krystal\Http\FileTransfer\FileUploadTrait;
use Menu\Service\MenuWidgetInterface;

/**
 * Very common services for the widget must be here
 */
abstract class AbstractManager extends AbstractService
{
    use FileUploadTrait;

    /**
     * Menu widget manager
     * 
     * @var \Menu\Service\MenuWidgetInterface
     */
    protected $menuWidget;

    /**
     * Sets menu widget service
     * 
     * @param \Menu\Service\MenuWidgetInterface $menuWidget
     * @return void
     */
    final public function setMenuWidget(MenuWidgetInterface $menuWidget)
    {
        $this->menuWidget = $menuWidget;
    }

    /**
     * Uploads from the file data input
     * 
     * @param array $input Raw input data
     * @param string $group Primary group
     * @param string $attribute Target attribute
     * @param string $destination
     * @return void
     */
    final protected function appendFileData(array &$input, $group, $attribute, $destination)
    {
        // If explicit remove defined
        if (isset($input['data']['remove'][$attribute])) {
            $this->removeFileData($input['data']['remove'][$attribute]);
            $input['data'][$group][$attribute] = '';
        }

        if (isset($input['data'][$group], $input['files'][$group])) {
            // References
            $files =& $input['files'][$group];
            $data =& $input['data'][$group];

            $data[$attribute] = $this->uploadFileData($destination, $files[$attribute], $data[$attribute]);
        }
    }

    /**
     * Checks whether menu widget was injected
     * 
     * @return boolean
     */
    final protected function hasMenuWidget()
    {
        return $this->menuWidget instanceof MenuWidgetInterface;
    }

    /**
     * Adds menu item
     * 
     * @param string $id Last web page id
     * @param string $name Item's name
     * @param array $input Raw input data
     * @return boolean
     */
    final protected function addMenuItem($id, $name, array $input)
    {
        // If at least one menu widget it added
        if (isset($input['menu']['widget']) && is_array($input['menu']['widget'])) {
            return $this->menuWidget->add($input['menu']['widget'], $id, $name);
        }

        return false;
    }

    /**
     * Updates menu widget
     * 
     * @param string $id Web page id
     * @param string $name Item new name
     * @param array $input Raw input data
     * @return boolean
     */
    final protected function updateMenuItem($id, $name, array $input)
    {
        return $this->menuWidget->update($input, $id, $name);
    }
}
