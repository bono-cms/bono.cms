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

final class FacebookService extends AbstractSocialService
{
    /**
     * {@inheritDoc}
     */
    public function getShareLink()
    {
        return sprintf('http://www.facebook.com/sharer.php?u=%s', urlencode($this->url));
    }

    /**
     * {@inheritDoc}
     */
    public function getShareCount()
    {
        $crawler = new CurlHttplCrawler();
        $response = $crawler->get('http://graph.facebook.com/', array(
            'id' => $this->url
        ));

        $data = json_decode($response, true);

        if (is_array($data)) {
            return $data['share'];
        } else {
            return false;
        }
    }
}
