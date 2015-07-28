<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
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
		$post->setTimestamp(time())
			 ->setPublished(true)
			 ->setComments(true)
			 ->setSeo(true);

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
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
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {

			$postManager = $this->getPostManager();

			if ($postManager->add($this->request->getPost())) {

				$this->flashBag->set('success', 'A post has been created successfully');
				return $postManager->getLastId();
			}

		} else {

			return $formValidator->getErrors();
		}
	}
}
