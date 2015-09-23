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

use Cms\Storage\NotificationMapperInterface;
use Cms\Service\AbstractManager;
use Krystal\Stdlib\VirtualEntity;

final class NotificationManager extends AbstractManager implements NotificationManagerInterface
{
	/**
	 * Notification mapper
	 * 
	 * @var \Cms\Storage\NotificationMapperInterface
	 */
	private $notificationMapper;

	/**
	 * State initialization
	 * 
	 * @param \Cms\Storage\NotificationMapperInterface $notificationMapper
	 * @return void
	 */
	public function __construct(NotificationMapperInterface $notificationMapper)
	{
		$this->notificationMapper = $notificationMapper;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $notification)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $notification['id'])
				  ->setViewed((bool) $notification['viewed'])
				  ->setTimestamp((int) $notification['timestamp'])
				  // No need to filter "message" from XSS, because that message never comes from user's input
				  ->setMessage($notification['message']);
		
		return $entity;
	}

	/**
	 * Marks all messages as read
	 * 
	 * @return boolean
	 */
	public function nullify()
	{
		return $this->notificationMapper->nullify();
	}

	/**
	 * Returns amount of unviewed notifications
	 * 
	 * @return integer
	 */
	public function getUnviewedCount()
	{
		return $this->notificationMapper->countUnviewed();
	}

	/**
	 * Adds a new notification
	 * 
	 * @param string $message
	 * @return boolean
	 */
	public function notify($message)
	{
		return $this->notificationMapper->insert(time(), '0', $message);
	}

	/**
	 * Deletes a notification by its associated id
	 * 
	 * @param string $id Target id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->notificationMapper->deleteById($id);
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->notificationMapper->getPaginator();
	}

	/**
	 * Fetch all notification entities filtered by pagination
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->prepareResults($this->notificationMapper->fetchAllByPage($page, $itemsPerPage));
	}

	/**
	 * Clears all notifications
	 * 
	 * @return boolean
	 */
	public function clearAll()
	{
		return $this->notificationMapper->clearAll();
	}
}
