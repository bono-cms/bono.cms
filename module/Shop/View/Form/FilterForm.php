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

use Krystal\Form\FormType\AbstractFilterForm;

final class FilterForm extends AbstractFilterForm
{
	/**
	 * {@inheritDoc}
	 */
	public function getElements()
	{
		return array(
			'title' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'type' => 'text',
						'class' => 'form-control'
					)
				)
			),

			'id' => array(
				'element' => array(
					'type' => 'number',
					'attributes' => array(
						'type' => 'text',
						'class' => 'form-control col-md-1',
						'min' => '1'
					)
				)
			),

			'date' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'type' => 'text',
						'class' => 'form-control'
					)
				)
			),

			'price' => array(
				'element' => array(
					'type' => 'text',
					'attributes' => array(
						'type' => 'text',
						'class' => 'form-control'
					)
				)
			),
			
			'seo' => array(
				'element' => array(
					'type' => 'checkbox',
				)
			),
			
			'published' => array(
				'element' => array(
					'type' => 'checkbox',
				)
			),
			
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'filter';
	}
}
