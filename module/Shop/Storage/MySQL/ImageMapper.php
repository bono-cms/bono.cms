<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Shop\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Shop\Storage\ImageMapperInterface;

final class ImageMapper extends AbstractMapper implements ImageMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	protected $table = 'bono_module_shop_product_images';

	/**
	 * Returns shared select
	 * 
	 * @param string $productId
	 * @param boolean $published
	 * @return \Krystal\Db\Sql\Db
	 */
	private function getSelectQuery($productId, $published)
	{
		$db = $this->db->select('*')
					   ->from($this->table)
					   ->whereEquals('product_id', $productId);

		if ($published === true) {

			$db->andWhereEquals('published', '1')
			   ->orderBy('order');
		} else {

			$db->orderBy('id')
			   ->desc();
		}

		return $db;
	}
	
	/**
	 * Queries for result-set
	 * 
	 * @param string $productId
	 * @param boolean $published
	 * @return array
	 */
	private function getResults($productId, $published)
	{
		return $this->getSelectQuery($productId, $published)
					->queryAll();
	}

	/**
	 * Updates a row data by associated id
	 * 
	 * @param string $id
	 * @return boolean
	 */
	private function updateRowById($id, $column, $value)
	{
		return $this->db->update($this->table, array($column => $value))
						->whereEquals('id', $id)
						->execute();
	}
	
	/**
	 * Deletes all by associated column name and its value
	 * 
	 * @param string $column
	 * @param string $value
	 * @return boolean
	 */
	private function deleteAllByColumn($column, $value)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals($column, $value)
						->execute();
	}

	/**
	 * Fetches image's file name by its associated id
	 * 
	 * @param string $id Image's id
	 * @return string
	 */
	public function fetchFileNameById($id)
	{
		return $this->db->select('image')
						->from($this->table)
						->whereEquals('id', $id)
						->query('image');
	}

	/**
	 * Fetches all published images by associated product id
	 * 
	 * @param string $productId
	 * @return array
	 */
	public function fetchAllPublishedByProductId($productId)
	{
		return $this->getResults($productId, true);
	}

	/**
	 * Fetches all images by associated product id
	 * 
	 * @param string $productId
	 * @return array
	 */
	public function fetchAllByProductId($productId)
	{
		return $this->getResults($productId, false);
	}

	/**
	 * Adds an image
	 * 
	 * @param string $productId
	 * @param string $image Image's file name
	 * @param string $order Sort order
	 * @param string $published Whether image is published or not by default
	 * @return boolean
	 */
	public function insert($productId, $image, $order, $published)
	{
		return $this->db->insert($this->table, array(
			'product_id' => $productId,
			'image'	=> $image,
			'order' => $order,
			'published' => $published
			
		))->execute();
	}

	/**
	 * Updates image's filename by its associated id
	 * 
	 * @param string $id Image id
	 * @param string $filename Image filename
	 * @return boolean
	 */
	public function updateFileNameById($id, $filename)
	{
		return $this->updateRowById($id, 'image', $filename);
	}

	/**
	 * Updates sort order by image's associated id
	 * 
	 * @param string $id Image's id
	 * @param string $order New sort order
	 * @return boolean
	 */
	public function updateOrderById($id, $order)
	{
		return $this->updateRowById($id, 'order', $order);
	}

	/**
	 * Updates image's published state
	 * 
	 * @param string $id Image's id
	 * @param string $published New state, either 1 or 0
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateRowById($id, 'published', $published);
	}

	/**
	 * Delete an image by its associated id
	 * 
	 * @param string $id
	 * @return boolean Depending on success
	 */
	public function deleteById($id)
	{
		return $this->deleteAllByColumn('id', $id);
	}

	/**
	 * Deletes all images by associated product id
	 * 
	 * @param string $productId
	 * @return boolean
	 */
	public function deleteAllByProductId($productId)
	{
		return $this->deleteAllByColumn('product_id', $productId);
	}
}
