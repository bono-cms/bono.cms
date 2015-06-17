<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Storage;

interface FormMapperInterface
{
	/**
	 * Updates SEO state by form's associated id
	 * 
	 * @param string $id
	 * @param string $seo
	 * @return boolean
	 */
	public function updateSeoById($id, $seo);

	/** Adds new form
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Updates a form
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data);

	/**
	 * Fetches form data by its associated id
	 * 
	 * @param string $id Form's id
	 * @return array
	 */
	public function fetchById($id);

	/**
	 * Fetches all forms
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Deletes a form by its associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function deleteById($id);
}
