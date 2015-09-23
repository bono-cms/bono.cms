<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Service;

use Cms\Service\HistoryManagerInterface;
use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Menu\Service\MenuWidgetInterface;
use Menu\Contract\MenuAwareManager;
use MailForm\Storage\FormMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;

final class FormManager extends AbstractManager implements FormManagerInterface, MenuAwareManager
{
	/**
	 * Any compliant form mapper
	 * 
	 * @var \MailForm\Storage\FormMapperInterface
	 */
	private $formMapper;

	/**
	 * Web page manager to deal with slugs
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * History manager to keep track of latest actions
	 * 
	 * @var \Cms\Service\HistoryManagerInterface
	 */
	private $historyManager;

	/**
	 * State initialization
	 * 
	 * @param \MailForm\Storage\FormMapperInterface $formMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @param \Menu\Service\MenuWidgetInterface $menuWidget
	 * @return void
	 */
	public function __construct(
		FormMapperInterface $formMapper,
		WebPageManagerInterface $webPageManager,
		HistoryManagerInterface $historyManager,
		MenuWidgetInterface $menuWidget = null
	){
		$this->formMapper = $formMapper;
		$this->webPageManager = $webPageManager;
		$this->historyManager = $historyManager;
		$this->setMenuWidget($menuWidget);
	}

	/**
	 * Fetches message view by associated form id
	 * 
	 * @param string $id Form id
	 * @return string
	 */
	public function fetchMessageViewById($id)
	{
		return $this->formMapper->fetchMessageViewById($id);
	}

	/**
	 * Updates SEO states by associated form ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updateSeo(array $pair)
	{
		foreach ($pair as $id => $seo) {
			if (!$this->formMapper->updateSeoById($id, $seo)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Fetches web page title by its associated id
	 * 
	 * @param string $webPageId
	 * @return string Web page title
	 */
	public function fetchTitleByWebPageId($webPageId)
	{
		return $this->formMapper->fetchTitleByWebPageId($webPageId);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $form)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $form['id'])
				->setLangId((int) $form['lang_id'])
				->setWebPageId((int) $form['web_page_id'])
				->setTitle(Filter::escape($form['title']))
				->setDescription(Filter::escapeContent($form['description']))
				->setSeo((bool) $form['seo'])
				->setSlug(Filter::escape($this->webPageManager->fetchSlugByWebPageId($form['web_page_id'])))
				->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
				->setPermanentUrl('/module/mail-form/'.$entity->getId())
				->setTemplate(Filter::escape($form['template']))
				->setMessageView(Filter::escape($form['message_view']))
				->setKeywords(Filter::escape($form['keywords']))
				->setMetaDescription(Filter::escape($form['meta_description']));

		return $entity;
	}

	/**
	 * Returns last id
	 * 
	 * @return integer
	 */
	public function getLastId()
	{
		return $this->formMapper->getLastId();
	}

	/**
	 * Deletes a page
	 * 
	 * @param string $id Form id
	 * @return boolean
	 */
	private function delete($id)
	{
		$webPageId = $this->formMapper->fetchWebPageIdById($id);
		$this->webPageManager->deleteById($webPageId);
		$this->formMapper->deleteById($id);

		return true;
	}

	/**
	 * Delete by collection of ids
	 * 
	 * @param array $ids
	 * @return boolean
	 */
	public function deleteByIds(array $ids)
	{
		foreach ($ids as $id) {
			$this->delete($id);
		}

		$this->track('Batch removal of %s mail forms', count($ids));
		return true;
	}

	/**
	 * Deletes a form by its associated id
	 * 
	 * @param string $id Form id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$title = $this->formMapper->fetchTitleById($id);

		if ($this->delete($id)) {
			$this->track('Mail form "%s" has been removed', $title);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Fetches all form entities
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		return $this->prepareResults($this->formMapper->fetchAll());
	}

	/**
	 * Fetches form entity by its associated id
	 * 
	 * @param string $id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->prepareResult($this->formMapper->fetchById($id));
	}

	/**
	 * Prepares an input before sending to the form mapper
	 * 
	 * @param array $input Raw input data
	 * @return array Prepared input
	 */
	private function prepareInput(array $input)
	{
		$form =& $input['form'];

		if (empty($form['slug'])) {
			// Empty slug is taken from a title
			$form['slug'] = $form['title'];
		}

		// Normalize a slug now
		$form['slug'] = $this->webPageManager->sluggify($form['slug']);

		return $input;
	}

	/**
	 * Adds a form
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$input = $this->prepareInput($input);
		$form =& $input['form'];

		$form['web_page_id'] = '';

		if ($this->formMapper->insert(ArrayUtils::arrayWithout($form, array('slug', 'menu')))) {

			// Add a web page now
			if ($this->webPageManager->add($this->getLastId(), $form['slug'], 'Mail forms', 'MailForm:Form@indexAction', $this->formMapper)) {

				if ($this->hasMenuWidget()) {
					$this->addMenuItem($this->webPageManager->getLastId(), $form['title'], $input);
				}
			}

			$this->track('Mail form "%s" has been created', $form['title']);
			return true;

		} else {
			return false;
		}

		return true;
	}

	/**
	 * Updates a form
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		$input = $this->prepareInput($input);
		$form = $input['form'];

		if ($this->formMapper->update(ArrayUtils::arrayWithout($form, array('slug', 'menu')))) {

			$this->webPageManager->update($form['web_page_id'], $form['slug']);

			if ($this->hasMenuWidget() && isset($input['menu'])) {
				$this->updateMenuItem($form['web_page_id'], $form['title'], $input['menu']);
			}

			$this->track('Mail form "%s" has been updated', $form['title']);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Tracks activity
	 * 
	 * @param string $message
	 * @param string $placeholder
	 * @return boolean
	 */
	private function track($message, $placeholder = '')
	{
		return $this->historyManager->write('MailForm', $message, $placeholder);
	}
}
