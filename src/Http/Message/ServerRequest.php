<?php
/**
 * This file is part of the O2System Reactor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */

// ------------------------------------------------------------------------

namespace O2System\Reactor\Http\Message;

// ------------------------------------------------------------------------

use O2System\Kernel\Http\Message;
use O2System\Kernel\Http\Router\DataStructures\Controller;

/**
 * Class ServerRequest
 *
 * @package O2System\Reactor\Http\Message
 */
class ServerRequest extends Message\ServerRequest implements \IteratorAggregate
{
    /**
     * Request::$controller
     *
     * Requested Controller FilePath
     *
     * @var string Controller FilePath.
     */
    protected $controller;

    // ------------------------------------------------------------------------

    /**
     * Request::getLanguage
     *
     * @return string
     */
    public function getLanguage()
    {
        return language()->getDefault();
    }

    //--------------------------------------------------------------------

    /**
     * Request::getController
     *
     * @return bool|Controller
     */
    public function getController()
    {
        if (false !== ($controller = services('controller'))) {
            return $controller;
        }

        return false;
    }
}