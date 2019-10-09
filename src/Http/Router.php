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

namespace O2System\Reactor\Http;

// ------------------------------------------------------------------------

use O2System\Kernel\Http;

/**
 * Class Router
 * @package O2System\Reactor\Http
 */
class Router extends Http\Router
{
    public function handle(Http\Message\Uri $uri = null)
    {
        // Load app addresses config
        $this->addresses = config()->loadFile('addresses', true);

        return parent::handle($uri);
    }
}
