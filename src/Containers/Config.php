<?php
/**
 * This file is part of the O2System Framework package.
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

use O2System\Kernel\DataStructures\Input\Env;
use O2System\Spl\DataStructures\SplArrayObject;
use O2System\Spl\Traits\Collectors\FilePathCollectorTrait;

/**
 * Class Config
 *
 * @package O2System\Reactor\Containers
 */
class Config extends Env
{
    use FilePathCollectorTrait;

    /**
     * Config::$loaded
     *
     * @var array
     */
    protected $loaded = [];

    // ------------------------------------------------------------------------

    /**
     * Config::__construct
     */
    public function __construct()
    {
        $this->setFileDirName('Config');
        $this->addFilePath(PATH_REACTOR);
        $this->addFilePath(PATH_APP);

        $this->loadFile('Config');

        // Set default timezone
        if (isset($this->storage[ 'datetime' ][ 'timezone' ])) {
            date_default_timezone_set($this->storage[ 'datetime' ][ 'timezone' ]);
        }

        // Setup Language Ideom and Locale
        if (isset($this->storage[ 'language' ])) {
            language()->setDefault($this->storage[ 'language' ]);
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

        foreach ($this->filePaths as $configFilePath) {
            if (is_file(
                $filePath = $configFilePath . ucfirst(
                        strtolower(ENVIRONMENT)
                    ) . DIRECTORY_SEPARATOR . $configFile . '.php'
            )) {
                include($filePath);
            } elseif (is_file($filePath = $configFilePath . DIRECTORY_SEPARATOR . $configFile . '.php')) {
                include($filePath);
            }
        }
        
        if($offset === 'config') {
            $this->exchangeArray($$offset);

            return true;
        } elseif (isset($$offset)) {
            if ( ! in_array($offset, $this->loaded)) {
                array_push($this->loaded, $offset);
            }

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
     * @return mixed|\O2System\Spl\DataStructures\SplArrayObject
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

    // ------------------------------------------------------------------------

    /**
     * Config::reload
     */
    public function reload()
    {
        if(count($this->loaded)) {
            foreach($this->loaded as $filename) {
                $this->loadFile($filename);
            }
        }
    }
}