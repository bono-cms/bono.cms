<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\View\Form;

use Krystal\Validate\Pattern;
use Krystal\Form\FormType\AbstractRegularForm;

final class ProductForm extends AbstractRegularForm
{
	/**
	 * {@inheritDoc}
	 */
	public function getElements()
	{
		return array(

			'id' => array(
				'element' => array(
					'type' => 'hidden',
				),

				'pk' => true
			),

			'cover' => array(
				'element' => array(
					'type' => 'hidden'
				)
			),

			'web_page_id' => array(
				'element' => array(
					'type' => 'hidden'
				)
			),

			'category_id' => array(
				'element' => array(
					'type' => 'select',
					'list' => $this->getData('categories'),
					'attributes' => array(
						'class' => 'form-control'
					)
				)
			),

			'description' => array(
				'element' => array(
					'type' => 'textarea',
					'attributes' => array()
				)
			),
			
			'title' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'Product title',
					),
					'rules' => array(
						//new Pattern\Title()
					)
				)
			),
			
			'published' => array(
				'element' => array(
					'type' => 'checkbox',
					'attributes' => array(
						'class' => 'form-control'
					)
				)
			),
			
			'seo' => array(
				'element' => array(
					'type' => 'checkbox',
					'attributes' => array(
						'class' => 'form-control'
					)
				)
			),
			
			'slug' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'URL slug for this product. By default is taken from a title'
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
			),
			
			'order' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'Order of the product to be sorted'
					)
				)
			),
			
			'special_offer' => array(
				'element' => array(
					'type' => 'checkbox',
					'attributes' => array(
						'class' => 'form-control'
					)
				)
			),
			
			'regular_price' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'Price of the product to be added',
						'step' => '0.01',
						'min' => '1'
					)
				)
			),
			
			'stoke_price' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'form-control',
						'placeholder' => 'Stoke price'
					)
				)
			),
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'product';
	}
}
