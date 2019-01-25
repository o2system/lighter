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

if ( ! function_exists('o2system')) {
    /**
     * o2system
     *
     * Convenient shortcut for O2System Framework Instance
     *
     * @return O2System\Framework
     */
    function o2system()
    {
        return O2System\Reactor::getInstance();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('loader')) {
    /**
     * loader
     *
     * Convenient shortcut for O2System Framework Loader service.
     *
     * @return O2System\Framework\Services\Loader
     */
    function loader()
    {
        return services('loader');
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('config')) {
    /**
     * config
     *
     * Convenient shortcut for O2System Framework Config service.
     *
     * @return O2System\Framework\Services\Config|\O2System\Kernel\Datastructures\Config
     */
    function config()
    {
        $args = func_get_args();

        if ($countArgs = count($args)) {
            if(services()->has('config')) {
                $config = services('config');

                if ($countArgs == 1) {
                    return call_user_func_array([&$config, 'getItem'], $args);
                } else {
                    return call_user_func_array([&$config, 'loadFile'], $args);
                }
            }
        }

        return services('config');
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('cache')) {
    /**
     * cache
     *
     * Convenient shortcut for O2System Framework Cache service.
     *
     * @return O2System\Framework\Services\Cache|boolean Returns FALSE if service not exists.
     */
    function cache()
    {
        if(services()->has('cache')) {
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
     * @return O2System\Framework\Services\Hooks Returns FALSE if service not exists.
     */
    function hooks()
    {
        if(services()->has('hooks')) {
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
     * @return O2System\Framework\Containers\Models|O2System\Framework\Models\Sql\Model|O2System\Framework\Models\NoSql\Model
     */
    function models()
    {
        $args = func_get_args();

        if (count($args)) {
            return o2system()->models->get($args[ 0 ]);
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
     * @return O2System\Framework\Http\Router|O2System\Framework\Cli\Router
     */
    function router()
    {
        return services('router');
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
            if(isset($_SESSION[ $args[0] ])) {
                return $_SESSION[ $args[0] ];
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
     * @return O2System\Framework\Http\Middleware
     */
    function middleware()
    {
        return services('middleware');
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('controller')) {
    /**
     * controller
     *
     * Convenient shortcut for O2System Framework Controller service.
     *
     * @return O2System\Framework\Http\Controller|bool
     */
    function controller()
    {
        $args = func_get_args();

        if (count($args)) {
            $controller = services()->get('controller');

            return call_user_func_array([&$controller, '__call'], $args);
        }

        return services('controller');
    }
}