<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Menu\View;

use Krystal\Form\NodeElement;

/* Special drop-down renderer made for Twitter bootstrap 3.x */
final class BootstrapDropdown extends AbstractSiteDropdown
{
	/**
	 * {@inheritDoc}
	 */
	protected function getChildOpener(array $row, array $parents, $active)
	{
		$li = new NodeElement();
		$li->openTag('li');

		// Determine whether target id has at least one child
		$hasChildren = $this->hasChildren($row['id'], $parents);

		if ($hasChildren) {
			$li->addAttribute('class', 'dropdown');
		}

		// Is it active web page?
		if (isset($row['web_page_id']) && $row['web_page_id'] != 0 && $active != 0 && $row['web_page_id'] == $active) {
			$li->addAttribute('class', 'active');
		}

		$a = new NodeElement();
		$a->openTag('a')
		  ->addAttribute('href', $this->makeUrl($row));

		// Whether to open in new window?
		if ((bool) $row['open_in_new_window']) {
			$a->addAttribute('target', '_blank');
		}

		if (!empty($row['hint'])) {
			$a->addAttribute('title', $row['hint']);
		}

		if ($hasChildren) {
			$a->addAttributes(array(
				'class' => 'dropdown-toggle',
				'data-toggle' => 'dropdown',
				'role' => 'button',
				'aria-expanded' => 'false'
			));
		}

		$a->setText($row['name'].PHP_EOL);

		if ($hasChildren) {
			$span = new NodeElement();
			$span->openTag('span')
			     ->addAttribute('class', 'caret')
				 ->finalize()
				 ->closeTag();

			$a->appendChild($span);
		}

		$a->closeTag();
		$li->appendChild($a);

		return $li->render();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getParentCloser()
	{
		$ul = new NodeElement();
		$ul->closeTag('ul');

		return $ul->render();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getChildCloser()
	{
		$li = new NodeElement();
		$li->closeTag('li');

		return $li->render();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFirstLevelParent()
	{
		$ul = new NodeElement();
		$ul->openTag('ul')
		   ->addAttribute('class', 'nav navbar-nav');

		// Check whether we have a class name
		if (isset($this->options['class']['base'])) {
			$ul->addAttribute('class', $this->options['class']['base']);
		}

		return $ul->finalize()
				  ->render();
	}

	/**
 	 * {@inheritDoc}
	 */
	protected function getNestedLevelParent()
	{
		$ul = new NodeElement();

		return $ul->openTag('ul')
				  ->addAttribute('class', 'dropdown-menu')
				  ->addAttribute('role', 'menu')
				  ->finalize()
				  ->render();
	}
}
