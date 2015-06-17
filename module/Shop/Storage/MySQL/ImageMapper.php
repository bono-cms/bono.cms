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
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('product_id', $productId)
						->andWhereEquals('published', '1')
						->orderBy('order')
						->queryAll();
	}

	/**
	 * Fetches all images by associated product id
	 * 
	 * @param string $productId
	 * @return array
	 */
	public function fetchAllByProductId($productId)
	{
		return $this->db->select('*')
						->from($this->table)
						->whereEquals('product_id', $productId)
						->orderBy('id')
						->desc()
						->queryAll();
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
	 * Deletes all images by associated product id
	 * 
	 * @param string $productId
	 * @return boolean
	 */
	public function deleteAllByProductId($productId)
	{
		return $this->db->delete()
						->from($this->table)
						->whereEquals('product_id', $productId)
						->execute();
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
		return $this->db->delete()
						->from($this->table)
						->whereEquals('id', $id)
						->execute();
	}
}
