<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Pages\View\Form;

use Krystal\Form\FormType\AbstractRegularForm;

final class PageForm extends AbstractRegularForm
{
	/**
	 * {@inheritDoc}
	 */
	public function getElements()
	{
		return array(
			'id' => array(
				'element' => array(
					'type' => 'hidden'
				),

				'pk' => true
			),

			'web_page_id' => array(
				'element' => array(
					'type' => 'hidden'
				)
			),

			'title' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'Page title'
					)
				)
			),

			'content' => array(
				'element' => array(
					'type' => 'textarea',
					'attributes' => array(
						'class' => 'form-control'
					)
				)
			),

			'controller' => array(
				'element' => array(
					'type' => 'select',
					'list' => $this->getData('controllers'),
					'attributes' => array(
						'class' => 'form-control'
					)
				)
			),

			'template' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'If you need another template which is different from default one, you can define its name here'
					)
				)
			),

			'protected' => array(
				'element' => array(
					'type' => 'checkbox',
					'attributes' => array(
						'class' => 'form-control',
						'title' => 'If you protect the page you are going to created, them users that use only simple mode will not be able to remove it'
					)
				)
			),

			'makeDefault' => array(
				'element' => array(
					'type' => 'checkbox',
					'attributes' => array(
						'class' => 'form-control',
						'title' => 'Whether it should be home page or not'
					)
				)
			),

			'seo' => array(
				'element' => array(
					'type' => 'checkbox',
					'attributes' => array(
						'class' => 'form-control',
					)
				)
			),

			'slug' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'URL slug for this page. By default is taken from a title'
					)
				)
			),

			'keywords' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'Keywords used for search engines'
					)
				)
			),

			'meta_description' => array(
				'element' => array(
					'type' => 'textarea',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'Meta description for search engines'
					)
				)
			)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'page';
	}
}
