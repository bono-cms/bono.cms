<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
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
		$input = $this->request->getPost();
		$formValidator = $this->getValidator($input);

		if ($formValidator->isValid()) {

			if ($this->makeOrder($input)) {
				$this->flashBag->set('success', 'Your order has been sent! We will contact you soon. Thank you!');
				return '1';
			}

		} else {
			return $formValidator->getErrors();
		}
	}

	/**
	 * Makes an order
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	private function makeOrder(array $input)
	{
		$orderManager = $this->getModuleService('orderManager');

		if ($orderManager->make($input)) {

			$message = $this->view->renderRaw($this->moduleName, 'messages', 'order', array(
				'basketManager' => $this->getModuleService('basketManager'),
				'currency' => $this->getModuleService('configManager')->getEntity()->getCurrency(),
				'input' => $input
			));

			// Prepare the subject
			$subject = $this->translator->translate('You have a new order from %s', $input['name']);

			// Grab mailer service
			$mailer = $this->getService('Cms', 'mailer');
			return $mailer->send($subject, $message);

		} else {
			return false;
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
