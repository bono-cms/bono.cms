<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

use Krystal\Iso\ISO3166\Country;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;
use Krystal\Http\PersistentStorageInterface;
use Cms\Service\AbstractManager;
use Cms\Storage\LanguageMapperInterface;

final class LanguageManager extends AbstractManager implements LanguageManagerInterface
{
	/**
	 * Any-compliant language mapper
	 * 
	 * @var \Cms\Storage\LanguageMapperInterface
	 */
	private $languageMapper;

	/**
	 * Configuration handler
	 * 
	 * @var object
	 */
	private $config;

	/**
	 * Persistent storage adapter
	 * 
	 * @var \Krystal\Http\PersistentStorageInterface
	 */
	private $storage;

	/**
	 * That default key stored in a mapper
	 * 
	 * @const string
	 */
	const LANGUAGE_DEFAULT_KEY = 'default_language_id';

	/**
	 * Default language key
	 * 
	 * @const string
	 */
	const LANGUAGE_CONTENT_STORAGE_KEY = 'lang_id';

	/**
	 * Key which represents interface code's value
	 * 
	 * @const string
	 */
	const LANGUAGE_INTERFACE_STORAGE_KEY = 'interface_lang_code';

	/**
	 * State initialization
	 * 
	 * @param \Cms\Storage\LanguageMapperInterface $languageMapper
	 * @param object $config
	 * @param \Krystal\Http\PersistentStorageInterface $storage
	 * @return void
	 */
	public function __construct(LanguageMapperInterface $languageMapper, $config, PersistentStorageInterface $storage)
	{
		$this->languageMapper = $languageMapper;
		$this->config = $config;
		$this->storage = $storage;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $language)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $language['id'])
			->setName(Filter::escape($language['name']))
			->setPublished((bool) $language['published'])
			->setOrder((int) $language['order'])
			->setDefault((bool) $this->isDefault($language['id']))
			->setCode(Filter::escape($language['code']));
		
		return $entity;
	}

	/**
	 * Fetches dummy language entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchDummy()
	{
		return $this->toEntity(array(
			'id' => null,
			'name' => null,
			'published' => true,
			'order' => null,
			'code' => null
		));
	}

	/**
	 * Defines interface language
	 * 
	 * @param string $code New interface's language code
	 * @return \Admin\Service\LanguageManager
	 */
	public function setInterfaceLangCode($code)
	{
		$this->storage->set(self::LANGUAGE_INTERFACE_STORAGE_KEY, $code);
		return $this;
	}

	/**
	 * Defines current language id
	 * 
	 * @param string $id Current language id to be set
	 * @return \Admin\Service\LanguageManager
	 */
	public function setCurrentId($id)
	{
		$this->storage->set(self::LANGUAGE_CONTENT_STORAGE_KEY, $id);
		return $this;
	}

	/**
	 * Returns current language id
	 * 
	 * @return string
	 */
	public function getCurrentId()
	{
		if ($this->storage->has(self::LANGUAGE_CONTENT_STORAGE_KEY)) {
			return $this->storage->get(self::LANGUAGE_CONTENT_STORAGE_KEY);
		}

		if ($this->hasDefault()) {
			return $this->getDefaultId();
		}

		return null;
	}

	/**
	 * Fetches a language bag by current id
	 * 
	 * @return object|boolean
	 */
	public function fetchByCurrentId()
	{
		return $this->fetchById($this->getCurrentId());
	}

	/**
	 * Marks language id as a default one
	 * 
	 * @param string $id Language id to be marked as default
	 * @return boolean
	 */
	public function makeDefault($id)
	{
		return $this->config->write(array(self::LANGUAGE_DEFAULT_KEY => $id));
	}

	/**
	 * Returns language default id
	 * 
	 * @return integer
	 */
	public function getDefaultId()
	{
		return $this->config->get(self::LANGUAGE_DEFAULT_KEY);
	}

	/**
	 * Checks whether a language id is default
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function isDefault($id)
	{
		if ($this->hasDefault()) {
			return $this->config->get(self::LANGUAGE_DEFAULT_KEY) == $id;
		} else {
			return false;
		}
	}

	/**
	 * Checks whether we have a default language id
	 * 
	 * @return boolean
	 */
	public function hasDefault()
	{
		return $this->config->exists(self::LANGUAGE_DEFAULT_KEY);
	}

	/**
	 * Updates published values by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean Depending on success
	 */
	public function updatePublished(array $pair)
	{
		foreach ($pair as $id => $published) {
			if (!$this->languageMapper->updatePublishedById($id, $published)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Update orders by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateOrders(array $pair)
	{
		foreach ($pair as $id => $order) {
			if (!$this->languageMapper->updateOrderById($id, $order)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Return all available countries in ISO3166-compliant
	 * 
	 * @return array
	 */
	public function getCountries()
	{
		$country = new Country();
		return $country->getAll();
	}

	/**
	 * Returns last language id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->languageMapper->getLastId();
	}

	/**
	 * Adds a language
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		if ($this->languageMapper->insert(ArrayUtils::arrayWithout($input, array('default')))) {
			if ($input['default'] == '1') {
				$this->makeDefault($this->getLastId());
			}

			return true;
		}

		return false;
	}

	/**
	 * Updates a language
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->languageMapper->update(ArrayUtils::arrayWithout($input, array('default')));
	}

	/**
	 * Fetches language's entity by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->languageMapper->fetchById($id));
	}

	/**
	 * Fetches language bag by its associated code
	 * 
	 * @param string $code
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	public function fetchByCode($code)
	{
		return $this->prepareResult($this->languageMapper->fetchByCode($code));
	}

	/**
	 * Fetches all language entities
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->prepareResults($this->languageMapper->fetchAll());
	}

	/**
	 * Fetches all only published languages bags
	 * 
	 * @return array
	 */
	public function fetchAllPublished()
	{
		return $this->prepareResults($this->languageMapper->fetchAllPublished());
	}

	/**
	 * Deletes a language by its associated id
	 * 
	 * @param string $id
	 * @return void
	 */
	public function deleteById($id)
	{
		return $this->languageMapper->deleteById($id);
	}
}
