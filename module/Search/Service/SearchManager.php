<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Service;

use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Search\Storage\SearchMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Text\TextTrimmer;

final class SearchManager extends AbstractManager implements SearchManagerInterface
{
	/**
	 * Any compliant search mapper
	 * 
	 * @var \Search\Storage\SearchMapperInterface
	 */
	private $searchMapper;

	/**
	 * Web page manager to generate URLs from web page ids
	 * 
	 * @var \Cms\Service\WebPageManagerInterface
	 */
	private $webPageManager;

	/**
	 * A keyword to be searched
	 * 
	 * @var string
	 */
	private $keyword;

	/**
	 * Maximal description's length for content
	 * By default 100 (which is enough for most cases)
	 * 
	 * @var integer
	 */
	private $maxDescriptionLength = 100;

	/**
	 * A wrapper for the matching word
	 * 
	 * @const string 
	 */
	const HIGHLIGHT_PATTERN = '<b><em>%s</em></b>';

	/**
	 * State initialization
	 * 
	 * @param \Search\Storage\SearchMapperInterface $searchMapper
	 * @param \Cms\Service\WebPageManagerInterface $webPageManager
	 * @return string
	 */
	public function __construct(SearchMapperInterface $searchMapper, WebPageManagerInterface $webPageManager)
	{
		$this->searchMapper = $searchMapper;
		$this->webPageManager = $webPageManager;
	}

	/**
	 * Overrides maximal description length
	 * 
	 * @param integer $maxDescriptionLength
	 * @return void
	 */
	public function setMaxDescriptionLength($maxDescriptionLength)
	{
		$this->maxDescriptionLength = $maxDescriptionLength;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function toEntity(array $result)
	{
		$entity = new VirtualEntity();
		$entity->setLangId($result['lang_id'])
			   ->setWebPageId($result['web_page_id'])
			   ->setTitle($this->highlight($result['title']))
			   ->setContent($this->highlight($this->trimContent($result['content'])))
			   ->setUrl($this->webPageManager->getUrl($entity->getWebPageId(), $entity->getLangId()));
		
		return $entity;
	}

	/**
	 * Highlight a matching keyword in a string
	 * 
	 * @param string $text
	 * @return string
	 */
	private function highlight($text)
	{
		return str_replace($this->keyword, sprintf(self::HIGHLIGHT_PATTERN, $this->keyword), $text);
	}

	/**
	 * Trims content
	 * 
	 * @param string $content
	 * @return string
	 */
	private function trimContent($content)
	{
		$trimmer = new TextTrimmer();
		return $trimmer->trim($content, $this->maxDescriptionLength);
	}

	/**
	 * Queries all attached mappers
	 * 
	 * @param string $data Query data
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function findByKeyword($keyword, $page, $itemsPerPage)
	{
		$this->keyword = $keyword;
		return $this->prepareResults($this->searchMapper->queryAll($keyword, $page, $itemsPerPage));
	}

	/**
	 * Returns prepared paginator's instance
	 * 
	 * @return \Krystal\Paginate\Paginator
	 */
	public function getPaginator()
	{
		return $this->searchMapper->getPaginator();
	}
}
