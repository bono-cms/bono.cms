<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Notepad extends AbstractController
{
	/**
	 * Shows a notepad
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadPlugins();

		return $this->view->render('notepad', array(
			'content' => $this->getNotepadManager()->fetch(),
			'title' => 'Notepad'
		));
	}

	/**
	 * Loads required plugins
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->load($this->getWysiwygPluginName())
				   ->appendScript($this->getWithAssetPath('/admin/notepad.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => '#',
				'name' => 'Notepad'
			)
		));
	}

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
	 * Saves notepad's data
	 * 
	 * @return string
	 */
	public function saveAction()
	{
		$content = $this->request->getPost('notepad');

		if ($this->getNotepadManager()->store($content)) {

			$this->flashMessenger->set('success', 'Notepad has been updated successfully');
			return '1';
		}
	}
}
