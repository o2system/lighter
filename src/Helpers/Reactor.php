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

if ( ! function_exists('o2system')) {
    /**
     * o2system
     *
     * Convenient shortcut for O2System Framework Instance
     *
     * @return O2System\Reactor
     */
    function o2system()
    {
        return O2System\Reactor::getInstance();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('config')) {
    /**
     * config
     *
     * Convenient shortcut for O2System Framework Config service.
     *
     * @return O2System\Reactor\Containers\Config|\O2System\Kernel\DataStructures\Config
     */
    function config()
    {
        $args = func_get_args();

        if ($numArgs = count($args)) {
            $config = o2system()->config;

            if ($numArgs == 1) {
                return call_user_func_array([&$config, 'getItem'], $args);
            } else {
                return call_user_func_array([&$config, 'loadFile'], $args);
            }
        }

        return o2system()->config;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('globals')) {
    /**
     * globals
     *
     * Convenient shortcut for O2System Framework globals container.
     *
     * @return mixed|O2System\Reactor\Containers\Globals
     */
    function globals()
    {
        $args = func_get_args();

        if (count($args)) {
            if (isset($GLOBALS[ $args[ 0 ] ])) {
                return $GLOBALS[ $args[ 0 ] ];
            }

            return null;
        }

        return o2system()->globals;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('env')) {
    /**
     * env
     *
     * Convenient shortcut for O2System Framework environment container.
     *
     * @return mixed|O2System\Reactor\Containers\Globals
     */
    function env()
    {
        $args = func_get_args();

        if (count($args)) {
            if (isset($_ENV[ $args[ 0 ] ])) {
                return $_ENV[ $args[ 0 ] ];
            }

            return null;
        }

        return o2system()->environment;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('loader')) {
    /**
     * loader
     *
     * Convenient shortcut for O2System Framework Loader service.
     *
     * @return O2System\Reactor\Services\Loader|bool Returns FALSE if failed
     */
    function loader()
    {
        if (services()->has('loader')) {
            return services()->get('loader');
        }

        return false;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('cache')) {
    /**
     * cache
     *
     * Convenient shortcut for O2System Framework Cache service.
     *
     * @return O2System\Reactor\Services\Cache|boolean Returns FALSE if service not exists.
     */
    function cache()
    {
        if (services()->has('cache')) {
            return services()->get('cache');
        }

        return false;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('hooks')) {
    /**
     * hooks
     *
     * Convenient shortcut for O2System Framework Hooks service.
     *
     * @return O2System\Reactor\Services\Hooks|bool Returns FALSE if service not exists.
     */
    function hooks()
    {
        if (services()->has('hooks')) {
            return services()->get('hooks');
        }

        return false;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('database')) {
    /**
     * database
     *
     * Convenient shortcut for O2System Framework Database Connection pools.
     *
     * @return O2System\Database\Connections
     */
    function database()
    {
        return models()->database;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('models')) {
    /**
     * models
     *
     * Convenient shortcut for O2System Framework Models container.
     *
     * @return O2System\Reactor\Containers\Models|O2System\Reactor\Models\Sql\Model|O2System\Reactor\Models\NoSql\Model
     */
    function models()
    {
        $args = func_get_args();

        if (count($args) == 1) {
            return o2system()->models->autoload($args[ 0 ]);
        } else if (count($args) == 2) {
            return o2system()->models->autoload($args[ 0 ], $args[ 1 ]);
        }

        return o2system()->models;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('router')) {
    /**
     * router
     *
     * Convenient shortcut for O2System Framework Router service.
     *
     * @return O2System\Reactor\Http\Router|O2System\Kernel\Cli\Router
     */
    function router()
    {
        if (services()->has('router')) {
            return services('router');
        }

        return false;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('session')) {
    /**
     * session
     *
     * Convenient shortcut for O2System Framework Session service.
     *
     * @return mixed|O2System\Session
     */
    function session()
    {
        $args = func_get_args();

        if (count($args)) {
            if (isset($_SESSION[ $args[ 0 ] ])) {
                return $_SESSION[ $args[ 0 ] ];
            }

            return null;
        }

        return services('session');
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('middleware')) {
    /**
     * O2System
     *
     * Convenient shortcut for O2System Framework Http Middleware service.
     *
     * @return O2System\Reactor\Http\Middleware
     */
    function middleware()
    {
        if (services()->has('middleware')) {
            return services('middleware');
        }

        return false;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('controller')) {
    /**
     * controller
     *
     * Convenient shortcut for O2System Framework Controller service.
     *
     * @return O2System\Reactor\Http\Controller|\O2System\Kernel\Http\Controller|\O2System\Kernel\Http\Router\DataStructures\Controller|bool
     */
    function controller()
    {
        if (services()->has('controller')) {
            $args = func_get_args();

            if (count($args)) {
                $controller = services()->get('controller');

                return call_user_func_array([&$controller, '__call'], $args);
            }

            return services('controller');
        }

        return false;
    }
}