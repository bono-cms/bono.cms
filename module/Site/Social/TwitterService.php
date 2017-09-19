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

final class TwitterService extends AbstractSocialService
{
    /**
     * {@inheritDoc}
     */
    public function getShareLink()
    {
        return sprintf('http://twitter.com/share?url=%s', urlencode($this->url));
    }

    /**
     * {@inheritDoc}
     */
    public function getShareCount()
    {
        // Not supported officially
    }
}
