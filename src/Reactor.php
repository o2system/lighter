<?php
/**
 * This file is part of the O2System PHP Reactor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */

// ------------------------------------------------------------------------

namespace O2System;

// ------------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 * ERROR REPORTING
 * ---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (strtoupper(ENVIRONMENT)) {
    case 'DEVELOPMENT':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;
    case 'TESTING':
    case 'PRODUCTION':
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', true, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}

/*
 *---------------------------------------------------------------
 * VENDOR PATH
 *---------------------------------------------------------------
 *
 * RealPath to vendor folder.
 *
 * WITH TRAILING SLASH!
 */
if ( ! defined('PATH_VENDOR')) {
    define('PATH_VENDOR', PATH_ROOT . 'vendor' . DIRECTORY_SEPARATOR);
}

/*
 *---------------------------------------------------------------
 * FRAMEWORK PATH
 *---------------------------------------------------------------
 *
 * RealPath to framework folder.
 *
 * WITH TRAILING SLASH!
 */
if ( ! defined('PATH_REACTOR')) {
    define('PATH_REACTOR', __DIR__ . DIRECTORY_SEPARATOR);
}

/*
 *---------------------------------------------------------------
 * APP PATH
 *---------------------------------------------------------------
 *
 * RealPath to application folder.
 *
 * WITH TRAILING SLASH!
 */
if ( ! defined('PATH_APP')) {
    define('PATH_APP', PATH_ROOT . DIR_APP . DIRECTORY_SEPARATOR);
}

/*
 *---------------------------------------------------------------
 * PUBLIC PATH
 *---------------------------------------------------------------
 *
 * RealPath to public folder.
 *
 * WITH TRAILING SLASH!
 */
if ( ! defined('PATH_PUBLIC')) {
    define('PATH_PUBLIC', PATH_ROOT . DIR_PUBLIC . DIRECTORY_SEPARATOR);
}

/*
 *---------------------------------------------------------------
 * CACHE PATH
 *---------------------------------------------------------------
 *
 * RealPath to writable caching folder.
 *
 * WITH TRAILING SLASH!
 */
if ( ! defined('PATH_CACHE')) {
    define('PATH_CACHE', PATH_ROOT . DIR_CACHE . DIRECTORY_SEPARATOR);
}

/*
 *---------------------------------------------------------------
 * STORAGE PATH
 *---------------------------------------------------------------
 *
 * RealPath to writable storage folder.
 *
 * WITH TRAILING SLASH!
 */
if ( ! defined('PATH_STORAGE')) {
    define('PATH_STORAGE', PATH_ROOT . DIR_STORAGE . DIRECTORY_SEPARATOR);
}

/*
 *---------------------------------------------------------------
 * DATABASE PATH
 *---------------------------------------------------------------
 *
 * RealPath to writable database folder.
 *
 * WITH TRAILING SLASH!
 */
if ( ! defined('PATH_DATABASE')) {
    define('PATH_DATABASE', PATH_ROOT . DIR_DATABASE . DIRECTORY_SEPARATOR);
}

/*
 *---------------------------------------------------------------
 * FRAMEWORK CONSTANTS
 *---------------------------------------------------------------
 */
require __DIR__ . '/Config/Constants.php';

/*
 *---------------------------------------------------------------
 * FRAMEWORK HELPERS
 *---------------------------------------------------------------
 */
require __DIR__ . '/Helpers/Reactor.php';

/**
 * Class Reactor
 *
 * @package O2System
 */
class Reactor extends Kernel
{
    /**
     * Reactor::$config
     *
     * Reactor Container Config
     *
     * @var Reactor\Containers\Config
     */
    public $config;

    /**
     * Reactor::$globals
     *
     * Reactor Container Globals
     *
     * @var Reactor\Containers\Globals
     */
    public $globals;

    /**
     * Reactor::$environment
     *
     * Reactor Container Environment
     *
     * @var Reactor\Containers\Environment
     */
    public $environment;

    /**
     * Reactor::$models
     *
     * Reactor Container Models
     *
     * @var Reactor\Containers\Models
     */
    public $models;

    // ------------------------------------------------------------------------

    /**
     * Reactor::__construct
     */
    protected function __construct()
    {
        parent::__construct();

        if (profiler() !== false) {
            profiler()->watch('Starting O2System Reactor');
        }

        // Instantiate Config Container
        if (profiler() !== false) {
            profiler()->watch('Starting Config Container');
        }

        $this->config = new Reactor\Containers\Config();

        if (profiler() !== false) {
            profiler()->watch('Starting Reactor Services');
        }

        $services = [
            'Services\Loader' => 'loader',
        ];
        
        foreach ($services as $className => $classOffset) {
            $this->services->load($className, $classOffset);
        }

        // Instantiate Config Container
        if (profiler() !== false) {
            profiler()->watch('Starting Config Container');
        }

        $this->config = new Reactor\Containers\Config();

        // Instantiate Globals Container
        if (profiler() !== false) {
            profiler()->watch('Starting Globals Container');
        }
        $this->globals = new Reactor\Containers\Globals();

        // Instantiate Environment Container
        if (profiler() !== false) {
            profiler()->watch('Starting Environment Container');
        }
        $this->environment = new Reactor\Containers\Environment();

        // Instantiate Models Container
        if (profiler() !== false) {
            profiler()->watch('Starting Models Container');
        }

        $this->models = new Reactor\Containers\Models();

        if (config()->loadFile('cache') === true) {
            // Instantiate Cache Service
            if (profiler() !== false) {
                profiler()->watch('Starting Cache Service');
            }
            
            $this->services->add(new Reactor\Services\Cache(config('cache', true)), 'cache');
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Reactor::__reconstruct
     */
    protected function __reconstruct()
    {
        if (is_cli()) {
            $this->cliHandler();
        } else {
            $this->httpHandler();
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Reactor::cliHandler
     *
     * @return void
     * @throws \ReflectionException
     */
    private function cliHandler()
    {
        // Instantiate CLI Router Service
        $this->services->load(Kernel\Cli\Router::class);

        if (profiler() !== false) {
            profiler()->watch('Parse Router Request');
        }
        router()->parseRequest();

        if ($commander = router()->getCommander()) {
            if ($commander instanceof Kernel\Cli\Router\DataStructures\Commander) {
                
                // Autoload Model
                $modelClassName = str_replace('Commanders', 'Models', $commander->getName());

                if (class_exists($modelClassName)) {
                    models()->load($modelClassName, 'commander');
                }

                if (profiler() !== false) {
                    profiler()->watch('Instantiating Requested Commander: ' . $commander->getClass());
                }
                $requestCommander = $commander->getInstance();

                if (profiler() !== false) {
                    profiler()->watch('Execute Requested Commander: ' . $commander->getClass());
                }
                $requestCommander->execute();

                exit(EXIT_SUCCESS);
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Reactor::httpHandler
     *
     * @return void
     */
    private function httpHandler()
    {
        // Instantiate Http Router Service
        $this->services->load(Reactor\Http\Router::class);

        if (profiler() !== false) {
            profiler()->watch('Parse Router Request');
        }
        router()->parseRequest();
        
        // Instantiate Http Middleware Service
        $this->services->load('Http\Middleware', 'middleware');

        if (profiler() !== false) {
            profiler()->watch('Running Middleware Service: Pre Controller');
        }
        middleware()->run();
        
        if($this->services->has('controller')) {
            $controller = $this->services->get('controller');
            
            // Autoload Model
            $modelClassName = str_replace('Controllers', 'Models', $controller->getName());

            if (class_exists($modelClassName)) {
                $this->models->register($modelClassName, 'controller');
            }

            if (profiler() !== false) {
                profiler()->watch('Instantiating Requested Controller: ' . $controller->getClass());
            }
            $requestController = $controller->getInstance();

            if (method_exists($requestController, '__reconstruct')) {
                $requestController->__reconstruct();
            } elseif (method_exists($requestController, 'initialize')) {
                $requestController->initialize();
            }

            $this->services->add($requestController, 'controller');

            if (profiler() !== false) {
                profiler()->watch('Calling Middleware Service: Post Controller');
            }
            middleware()->run();

            $requestMethod = $controller->getRequestMethod();
            $requestMethodArgs = $controller->getRequestMethodArgs();

            // Call the requested controller method
            if (profiler() !== false) {
                profiler()->watch('Execute Requested Controller Method');
            }

            $requestController->__call($requestMethod, $requestMethodArgs);
        } else {
            // Show Error (404) Page Not Found
            output()->sendError(404);
        }
    }
}
