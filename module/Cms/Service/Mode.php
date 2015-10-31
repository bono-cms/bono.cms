<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Service;

use Krystal\Http\PersistentStorageInterface;

final class Mode implements ModeInterface
{
    /**
     * Any compliant storage adapter
     * 
     * @var \Krystal\Http\PersistentStorageInterface
     */
    private $storage;

    const KEY = 'mode';
    const MODE_ADVANCED = 'advanced';
    const MODE_SIMPLE = 'simple';

    /**
     * State initialization
     * 
     * @param \Krystal\Http\PersistentStorageInterface $storage
     * @return void
     */
    public function __construct(PersistentStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Checks whether provided id belongs to current active mode
     * 
     * @param string $id
     * @return boolean
     */
    public function isCurrent($id)
    {
        return $this->fetch() == $id;
    }
    
    /**
     * Returns available modes
     * 
     * @return array
     */
    public function getModes()
    {
        return array(
            self::MODE_SIMPLE => 'Simple',
            self::MODE_ADVANCED => 'Advanced'
        );
    }

    /**
     * Writes mode value
     * 
     * @param string $value
     * @return void
     */
    private function write($value)
    {
        return $this->storage->set(self::KEY, $value);
    }

    /**
     * Prepares mode manager
     * 
     * @return void
     */
    public function prepare()
    {
        if ($this->fetch() === null) {
            $this->setSimple();
        }
    }

    /**
     * Fetches mode value from a storage
     * 
     * @return string
     */
    public function fetch()
    {
        if ($this->storage->has(self::KEY)) {
            return $this->storage->get(self::KEY);
        } else {
            return null;
        }
    }

    /**
     * Checks whether mode is advanced
     * 
     * @return boolean
     */
    public function isAdvanced()
    {
        return $this->fetch() == self::MODE_ADVANCED;
    }

    /**
     * Checks whether mode is simple
     * 
     * @return boolean
     */
    public function isSimple()
    {
        return $this->fetch() == self::MODE_SIMPLE;
    }

    /**
     * Activates advanced mode
     * 
     * @param string $value
     * @return void
     */
    public function setAdvanced()
    {
        return $this->write(self::MODE_ADVANCED);
    }

    /**
     * Activates simple mode
     * 
     * @return void
     */
    public function setSimple()
    {
        return $this->write(self::MODE_SIMPLE);
    }
}
