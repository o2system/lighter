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

use O2System\Database;
use O2System\Spl\Containers\DataStructures\SplServiceRegistry;
use O2System\Spl\Containers\SplServiceContainer;

/**
 * Class Models
 *
 * @package O2System\Reactor\Containers
 */
class Models extends SplServiceContainer
{
    /**
     * Models::$database
     *
     * @var \O2System\Database\Connections
     */
    public $database;

    // ------------------------------------------------------------------------

    /**
     * Models::__construct
     */
    public function __construct()
    {
        if ($config = config()->loadFile('database', true)) {
            if ( ! empty($config[ 'default' ][ 'hostname' ]) AND ! empty($config[ 'default' ][ 'username' ])) {

                if (profiler() !== false) {
                    profiler()->watch('Starting Database Service');
                }

                $this->database = new Database\Connections(
                    new Database\DataStructures\Config(
                        $config->getArrayCopy()
                    )
                );
            }
        }

        // Run models autoload
        $this->autoload();
    }

    // ------------------------------------------------------------------------

    private function autoload()
    {
        if (is_file(
            $filePath = PATH_APP . 'Config' . DIRECTORY_SEPARATOR . ucfirst(
                    strtolower(ENVIRONMENT)
                ) . DIRECTORY_SEPARATOR . 'Models.php'
        )) {
            include($filePath);
        } elseif (is_file($filePath = PATH_APP . 'Config' . DIRECTORY_SEPARATOR . 'Models.php')) {
            include($filePath);
        }

        if (isset($models) AND is_array($models)) {
            foreach ($models as $offset => $model) {
                if (is_string($model)) {
                    $this->load($model, $offset);
                } elseif (is_object($model)) {
                    $this->add($model);
                }
            }

            unset($models);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Models::load
     *
     * @param object|string $model
     * @param string|null   $offset
     */
    public function load($model, $offset = null)
    {
        if (is_string($model)) {
            if (class_exists($model)) {
                $service = new SplServiceRegistry($model);
            }
        } elseif ($model instanceof SplServiceRegistry) {
            $service = $model;
        }

        if (isset($service) && $service instanceof SplServiceRegistry) {
            if (profiler() !== false) {
                profiler()->watch('Load New Model: ' . $service->getClassName());
            }

            $this->register($service, $offset);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Models::register
     *
     * @param SplServiceRegistry $service
     * @param string|null        $offset
     */
    public function register(SplServiceRegistry $service, $offset = null)
    {
        if ($service instanceof SplServiceRegistry) {
            $offset = isset($offset)
                ? $offset
                : camelcase($service->getParameter());

            if ($service->isSubclassOf('O2System\Reactor\Models\Sql\Model') ||
                $service->isSubclassOf('O2System\Reactor\Models\NoSql\Model') ||
                $service->isSubclassOf('O2System\Reactor\Models\Files\Model')
            ) {
                $this->attach($offset, $service);

                if (profiler() !== false) {
                    profiler()->watch('Register New Model: ' . $service->getClassName());
                }
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Models::add
     *
     * @param \O2System\Reactor\Models\Sql\Model|\O2System\Reactor\Models\NoSql\Model|\O2System\Reactor\Models\Files\Model $model
     * @param null                                                                                                               $offset
     */
    public function add($model, $offset = null)
    {
        if (is_object($model)) {
            if ( ! $model instanceof SplServiceRegistry) {
                $model = new SplServiceRegistry($model);
            }
        }

        if (profiler() !== false) {
            profiler()->watch('Add New Model: ' . $model->getClassName());
        }

        $this->register($model, $offset);
    }
}