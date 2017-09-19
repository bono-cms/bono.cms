<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Site\Social;

use Krystal\Http\Client\CurlHttplCrawler;

final class VkService extends AbstractSocialService
{
    /**
     * {@inheritDoc}
     */
    public function getShareLink()
    {
        return sprintf('http://vk.com/share.php?url=%s', urlencode($this->url));
    }

    /**
     * {@inheritDoc}
     */
    public function getShareCount()
    {
        $data = array(
            'act' => 'count',
            'index' => '1',
            'url' => $this->url
        );

        $crawler = new CurlHttplCrawler();
        $response = $crawler->get('https://vk.com/share.php', $data, '?', array(CURLOPT_SSL_VERIFYPEER => false));

        // Extract share count from response
        preg_match('#VK.Share.count\(1, ([0-9]+)\);#', $response, $matches);

        return (count($matches) > 1) ? intval($matches[1]) : false;
    }
}
