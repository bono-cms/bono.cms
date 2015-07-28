<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Controller;

use Krystal\Validate\Pattern;

final class Order extends AbstractShopController
{
	/**
	 * Makes an orders
	 * 
	 * @return string
	 */
	public function orderAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid($this->request->getPost())) {

			// Grab a service which does everything behind the scenes
			$orderManager = $this->getModuleService('orderManager');

			if ($orderManager->make($this->request->getPost())) {

				$this->flashBag->set('success', 'Your order has been sent! We will contact you soon. Thank you!');
				return '1';
			}

		} else {

			return $formValidator->getErrors();
		}
	}

	/**
	 * Returns prepared form validator
	 * 
	 * @param array $input Raw input data
	 * @return \Krystal\Validate\ValidatorChain
	 */
	private function getValidator(array $input)
	{
		return $this->validatorFactory->build(array(
			'input' => array(
				'source' => $input,
				'definition' => array(
					'name' => new Pattern\Name(),
					'phone' => new Pattern\Phone(),
					'address' => new Pattern\Address(),
					'captcha' => new Pattern\Captcha($this->captcha)
				)
			)
		));
	}
}
