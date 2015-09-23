<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Photogallery\Controller\Admin\Album;

use Cms\Controller\Admin\AbstractController;
use Krystal\Tree\AdjacencyList\TreeBuilder;
use Krystal\Tree\AdjacencyList\Render\PhpArray;
use Krystal\Validate\Pattern;

abstract class AbstractAlbum extends AbstractController
{
	/**
	 * Returns shared form validator
	 * 
	 * @param array $post Raw input data
	 * @return \Krystal\Validate\ValidatorChain
	 */
	final protected function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					'name' => new Pattern\Name()
				)
			)
		));
	}

	/**
	 * Returns shared variables for Edit and Add controllers
	 * 
	 * @param array $overrides
	 * @return array
	 */
	final protected function getWithSharedVars(array $overrides)
	{
		$treeBuilder = new TreeBuilder($this->getAlbumManager()->fetchAll());

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'link' => 'Photogallery:Admin:Browser@indexAction',
				'name' => 'Photogallery'
			),
			
			array(
				'link' => '#',
				'name' => $overrides['title']
			)
		));
		
		$vars = array(
			'albums' => $treeBuilder->render(new PhpArray('title')) 
		);

		return array_replace_recursive($vars, $overrides);
	}

	/**
	 * Returns album manager service
	 * 
	 * @return \Photogallery\Service\AlbumManager
	 */
	final protected function getAlbumManager()
	{
		return $this->getModuleService('albumManager');
	}

	/**
	 * Returns template path
	 * 
	 * @return string
	 */
	final protected function getTemplatePath()
	{
		return 'album.form';
	}

	/**
	 * Loads shared plugins
	 * 
	 * @return void
	 */
	final protected function loadSharedPlugins()
	{
		$this->loadMenuWidget();
		$this->view->getPluginBag()->appendScript($this->getWithAssetPath('/admin/album.form.js'))
								   ->load($this->getWysiwygPluginName());
	}
}
