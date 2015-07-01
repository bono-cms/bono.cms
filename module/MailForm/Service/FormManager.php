<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Service;

use Cms\Service\HistoryManagerInterface;
use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Cms\Service\MailerInterface;
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
	 * Mailer service
	 * 
	 * @var \Cms\Service\MailerInterface
	 */
	private $mailer;

	/**
	 * State initialization
	 * 
	 * @param \MailForm\Storage\FormMapperInterface $formMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @param \Cms\Service\HistoryManagerInterface $historyManager
	 * @param \Cms\Service\MailerInterface $mailer
	 * @param \Menu\Service\MenuWidgetInterface $menuWidget
	 * @return void
	 */
	public function __construct(
		FormMapperInterface $formMapper,
		WebPageManagerInterface $webPageManager,
		HistoryManagerInterface $historyManager,
		MailerInterface $mailer,
		MenuWidgetInterface $menuWidget = null
	){
		$this->formMapper = $formMapper;
		$this->webPageManager = $webPageManager;
		$this->historyManager = $historyManager;
		$this->mailer = $mailer;
		$this->setMenuWidget($menuWidget);
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
	 * Sends a form
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function send(array $input)
	{
		$subject = sprintf('You received a new message from %s <%s>', $input['name'], $input['email']);
		return $this->mailer->send($subject, $this->getMessage($input));
	}

	/**
	 * Prepares a message
	 * 
	 * @param array $input
	 * @return string
	 */
	private function getMessage(array $input)
	{
		return $input['message'];
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
		//@TODO Track here
		
		return $this->delete($id);
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
		if (empty($input['slug'])) {
			// Empty slug is taken from a title
			$input['slug'] = $input['title'];
		}

		// Normalize a slug now
		$input['slug'] = $this->webPageManager->sluggify($input['slug']);

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
		$input['web_page_id'] = '';

		if ($this->formMapper->insert(ArrayUtils::arrayWithout($input, array('slug', 'menu')))) {

			// Add a web page now
			if ($this->webPageManager->add($this->getLastId(), $input['slug'], 'Mail forms', 'MailForm:Form@indexAction', $this->formMapper)) {

				if ($this->hasMenuWidget()) {
					$this->addMenuItem($this->webPageManager->getLastId(), $input['title'], $input);
				}
			}

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

		if ($this->formMapper->update(ArrayUtils::arrayWithout($input, array('slug', 'menu')))) {

			$this->webPageManager->update($input['web_page_id'], $input['slug']);

			if ($this->hasMenuWidget() && isset($input['menu'])) {
				$this->updateMenuItem($input['web_page_id'], $input['title'], $input['menu']);
			}

			return true;

		} else {
			return false;
		}
	}
}
