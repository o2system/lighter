<?php
/**
 * This file is part of the O2System PHP Framework package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */

// ------------------------------------------------------------------------

namespace O2System\Reactor\Containers;

// ------------------------------------------------------------------------

use O2System\Reactor\Containers\Environment;
use O2System\Spl\DataStructures\SplArrayObject;

/**
 * Class Config
 *
 * @package O2System\Reactor\Services
 */
class Config extends Environment
{
    /**
     * Config::__construct
     */
    public function __construct()
    {
        if (is_file(
            $filePath = PATH_APP . 'Config' . DIRECTORY_SEPARATOR . ucfirst(
                    strtolower(ENVIRONMENT)
                ) . DIRECTORY_SEPARATOR . 'Config.php'
        )) {
            include($filePath);
        } elseif (is_file($filePath = PATH_APP . 'Config' . DIRECTORY_SEPARATOR . 'Config.php')) {
            include($filePath);
        }

        if (isset($config) AND is_array($config)) {
            // Set default timezone
            if (isset($config['datetime']['timezone'])) {
                date_default_timezone_set($config['datetime']['timezone']);
            }

            $this->merge($config);

            unset($config);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Config::loadFile
     *
     * @param string $offset
     * @param bool   $return
     *
     * @return mixed
     */
    public function loadFile($offset, $return = false)
    {
        $basename = pathinfo($offset, PATHINFO_BASENAME);
        $filename = studlycase($basename);

        $configFile = str_replace($basename, $filename, $offset);
        $offset = camelcase($basename);

        $configDirs = [
            PATH_REACTOR . 'Config' . DIRECTORY_SEPARATOR,
            PATH_APP . 'Config' . DIRECTORY_SEPARATOR,
        ];

        foreach ($configDirs as $configDir) {
            if (is_file(
                $filePath = $configDir . ucfirst(
                        strtolower(ENVIRONMENT)
                    ) . DIRECTORY_SEPARATOR . $configFile . '.php'
            )) {
                include($filePath);
            } elseif (is_file($filePath = $configDir . DIRECTORY_SEPARATOR . $configFile . '.php')) {
                include($filePath);
            }
        }

        if (isset($$offset)) {
            $this->addItem($offset, $$offset);

            unset($$offset);

            if ($return) {
                return $this->getItem($offset);
            }

            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Config::addItem
     *
     * Adds config item.
     *
     * @param string $offset
     * @param mixed  $value
     */
    public function addItem($offset, $value)
    {
        $this->offsetSet($offset, $value);
    }

    // ------------------------------------------------------------------------

    /**
     * Config::getItem
     *
     * Gets config item.
     *
     * @param string $offset
     *
     * @return mixed|\O2System\Spl\Datastructures\SplArrayObject
     */
    public function &getItem($offset)
    {
        $item = parent::offsetGet($offset);

        if (is_array($item)) {
            if (is_string(key($item))) {
                $item = new SplArrayObject($item);
            }
        }

        return $item;
    }

    // ------------------------------------------------------------------------

    /**
     * Config::setItem
     *
     * Sets config item.
     *
     * @param string $offset
     * @param mixed  $value
     */
    public function setItem($offset, $value)
    {
        $this->offsetSet($offset, $value);
    }
}