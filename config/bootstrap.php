<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

gc_disable();

// Make paths relative to the root folder
chdir(dirname(__DIR__));

require('vendor/autoload.php');
require(__DIR__ . '/environment.php');

return \Krystal\Application\KernelFactory::build(require(__DIR__.'/app.php'));