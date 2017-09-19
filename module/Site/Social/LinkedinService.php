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

final class LinkedinService extends AbstractSocialService
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Linkedin';
    }

    /**
     * {@inheritDoc}
     */
    public function getShareLink()
    {
        return sprintf('https://www.linkedin.com/cws/share?url=%s', urlencode($this->url));
    }

    /**
     * {@inheritDoc}
     */
    public function getShareCount()
    {
        $crawler = new CurlHttplCrawler();
        $response = $crawler->get('https://www.linkedin.com/countserv/count/share', array(
            'url' => $this->url,
            'format' => 'json'
        ));

        $data = @json_decode($response, true);

        if (is_array($data)) {
            return $data['count'];
        } else {
            return false;
        }
    }
}
