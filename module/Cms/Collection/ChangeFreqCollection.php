<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Collection;

use Krystal\Stdlib\ArrayCollection;

final class ChangeFreqCollection extends ArrayCollection
{
    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        'a' => 'Always',
        'h' => 'Hourly',
        'd' => 'Daily',
        'w' => 'Weekly',
        'm' => 'Monthly',
        'y' => 'Yearly',
        'n' => 'Never'
    );
}
