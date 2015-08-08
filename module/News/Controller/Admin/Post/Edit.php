<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace News\Controller\Admin\Post;

final class Edit extends AbstractPost
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id Post id
	 * @return string
	 */
	public function indexAction($id)
	{
		$post = $this->getPostManager()->fetchById($id);

		if ($post !== false) {
			$this->loadSharedPlugins();

			return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
				'title' => 'Edit the post',
				'post' => $post,
			)));

		} else {

			return false;
		}
	}

	/**
	 * Updates a post
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('post'), $this->request->getFiles());

		if ($formValidator->isValid()) {

			if ($this->getPostManager()->update($this->request->getAll())) {

				$this->flashBag->set('success', 'A post has been updated successfully');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
