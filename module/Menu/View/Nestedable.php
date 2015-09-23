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

use Krystal\Tree\AdjacencyList\Render\AbstractDropdown;
use Krystal\Form\NodeElement;

/**
 * This plug-in is made for Nestedable JavaScript plug-in
 */
final class Nestedable extends AbstractDropdown
{
	/**
	 * {@inheritDoc}
	 */
	protected function getChildOpener(array $row, array $parents, $active)
	{
		$li = new NodeElement();
		$li->openTag('li');
		$li->addAttributes(array(
			'class' => 'dd-item', 
			'data-id' => $row['id']
		));

		// Nested div inside li
		$div = new NodeElement();
		$div->openTag('div');
		$div->addAttribute('class', 'dd-handle');

		if ($active == $row['id']) {
			$div->addAttribute('id', 'nestedactive');
		}

		$div->setText($row['name']);
		$div->closeTag();

		$li->appendChild($div);

		return $li->render();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getParentCloser()
	{
		$ol = new NodeElement();
		$ol->closeTag('ol');
		
		return $ol->render();
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
		$ol = new NodeElement();
		$ol->openTag('ol')
		   ->addAttribute('class', 'dd-list')
		   ->finalize();
		   
		return $ol->render();
	}

	/**
 	 * {@inheritDoc}
	 */
	protected function getNestedLevelParent()
	{
		// The same here
		return $this->getFirstLevelParent();
	}
}
