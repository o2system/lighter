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

namespace O2System\Reactor\Http;

// ------------------------------------------------------------------------

/**
 * Class Router
 *
 * @package O2System
 */
class Router extends \O2System\Kernel\Http\Router
{
    /**
     * Router::__construct
     */
    public function __construct()
    {
        // Load app addresses config
        $this->addresses = config()->loadFile('addresses', true);
    }
}
