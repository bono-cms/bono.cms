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

use Krystal\Http\Client\Curl;

final class GoogleService extends AbstractSocialService
{
    /**
     * {@inheritDoc}
     */
    public function getShareLink()
    {
        return sprintf('https://plus.google.com/share?url=%s', urlencode($this->url));
    }

    /**
     * {@inheritDoc}
     */
    public function getShareCount()
    {
        // HTTP POST parameters to be sent
        $data = array(
            'url' => $this->url,
            'method' => 'pos.plusones.get',
            'id' => 'p',
            'jsonrpc' => '2.0',
            'key' => 'p',
            'apiVersion' => 'v1',
            'params' => array(
                'nolog' => true,
                'id' => $this->url,
                'source' => 'widget',
                'userId' => '@viewer',
                'groupId' => '@self',
            )
        );

        $crawler = new Curl(array(
            CURLOPT_URL => 'https://clients6.google.com/rpc',
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json')
        ));

        $response = @json_decode($crawler->exec(), true);

        if (is_array($response)) {
            return $response['result']['metadata']['globalCounts']['count'];
        } else {
            return false;
        }
    }
}
