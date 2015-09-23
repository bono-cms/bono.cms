<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Blog\Controller\Admin\Post;

use Krystal\Stdlib\VirtualEntity;

final class Add extends AbstractPost
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		$post = new VirtualEntity();
		$post->setDate(date('m/d/Y', time()))
			 ->setPublished(true)
			 ->setComments(true)
			 ->setSeo(true);

		return $this->view->render($this->getTemplatePath(), $this->getWithSharedVars(array(
			'title' => 'Add a post',
			'post' => $post
		)));
	}

	/**
	 * Adds a post
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('post'));

		if ($formValidator->isValid()) {

			$postManager = $this->getPostManager();

			if ($postManager->add($this->request->getPost('post'))) {

				$this->flashBag->set('success', 'A post has been created successfully');
				return $postManager->getLastId();
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
