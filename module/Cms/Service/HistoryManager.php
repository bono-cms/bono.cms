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

use Cms\Service\AbstractManager;
use Cms\Storage\HistoryMapperInterface;
use Krystal\Stdlib\VirtualEntity;

final class HistoryManager extends AbstractManager implements HistoryManagerInterface
{
    /**
     * History mapper
     * 
     * @var \Cms\Storage\HistoryMapperInterface
     */
    private $historyMapper;

    /**
     * Tells whether history manager is enabled or not
     * 
     * @var boolean
     */
    private $enabled;

    /**
     * Target user's id who make changes
     * 
     * @var string
     */
    private $userId;

    /**
     * State initialization
     * 
     * @param \Cms\Storage\HistoryMapperInterface $historyMapper
     * @return void
     */
    public function __construct(HistoryMapperInterface $historyMapper)
    {
        $this->historyMapper = $historyMapper;
    }

    /**
     * Defines user's id that make changes
     * 
     * @param string $userId
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Sets whether history manager should be enabled or not
     * 
     * @param boolean $enabled
     * @return void
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Adds a record
     * 
     * @param string $module A target module
     * @param string $comment What have been done
     * @param string $placeholder
     * @return boolean
     */
    public function write($module, $comment, $placeholder)
    {
        // Do write in case enabled, otherwise ignore
        if ($this->isEnabled()) {

            $data = array(
                'timestamp' => time(),
                'user_id' => $this->userId,
                'module' => $module,
                'comment' => $comment,
                'placeholder' => $placeholder
            );
            
            return $this->historyMapper->insert($data);
            
        } else {
        
            return true;
        }
    }

    /**
     * Tells whether history manager is enabled
     * 
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Returns prepared paginator instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->historyMapper->getPaginator();
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $record)
    {
        $entity = new VirtualEntity();
        $entity->setId($record['id'], VirtualEntity::FILTER_INT)
            ->setTimestamp($record['timestamp'], VirtualEntity::FILTER_INT)
            ->setModule($record['module'], VirtualEntity::FILTER_TAGS)
            ->setUserId($record['user_id'], VirtualEntity::FILTER_INT)
            ->setComment($record['comment'], VirtualEntity::FILTER_TAGS)
            ->setPlaceholder($record['placeholder'], VirtualEntity::FILTER_TAGS);

        return $entity;
    }

    /**
     * Fetches all record entities filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Per page count
     * @return array
     */
    public function fetchAllByPage($page, $itemsPerPage)
    {
        return $this->prepareResults($this->historyMapper->fetchAllByPage($page, $itemsPerPage));
    }

    /**
     * Clear all records
     * 
     * @return boolean
     */
    public function clear()
    {
        return $this->historyMapper->clear();
    }

    /**
     * Deletes a record by its associated id
     * 
     * @param string $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->historyMapper->deleteById($id);
    }
}
