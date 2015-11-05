<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Install;

use PDO;
use PDOException;
use ReflectionClass;
use Krystal\Db\Sql\Connector\MySQL as Connector;
use Krystal\Config\File\FileArray;
use Krystal\Db\Sql\TableBuilder;

final class StorageInstaller implements StorageInstallerInterface
{
    /**
     * Installs storage-relevant data
     * 
     * @param string $path The path to database configuration file
     * @param string $dumpFile The path to the dump file
     * @param array $details
     * @return boolean
     */
    public function installFromDump($path, $dumpFile, array $details)
    {
        $pdo = $this->makePdo($details);

        if ($pdo !== false) {

            $builder = new TableBuilder($pdo);
            $builder->loadFromFile($dumpFile);
            $builder->run();

            $this->createConfigFile($path, $details);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Creates database configuration file
     * 
     * @param string $path
     * @param array $data Data to be written into configuration file
     * @return boolean
     */
    private function createConfigFile($path, array $data)
    {
        $cf = new FileArray($path);
        $cf->load();
        $cf->setConfig($data);
        return $cf->save();
    }

    /**
     * Attempts to build PDO instance
     * 
     * @param array $params Data to establish a connection
     * @return \PDO|boolean
     */
    private function makePdo(array $params)
    {
        try {
            $connector = new Connector();

            $relector = new ReflectionClass('PDO');
            $pdo = $relector->newInstanceArgs($connector->getArgs($params));

            return $pdo;

        } catch (PDOException $e) {
            return false;
        }
    }
}
