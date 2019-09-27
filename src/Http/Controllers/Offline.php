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

namespace O2System\Reactor\Http\Controllers;

// ------------------------------------------------------------------------

use O2System\Reactor\Http\Controllers\Restful as Controller;

/**
 * Class Offline
 * @package O2System\Reactor\Http\Controllers
 */
class Offline extends Controller
{
    /**
     * Offline::$inherited
     *
     * Controller inherited flag.
     *
     * @var bool
     */
    static public $inherited = true;

    // ------------------------------------------------------------------------

    /**
     * Offline::index
     */
    public function index()
    {
        $this->sendError(503, language('OFFLINE_MESSAGE'));
    }
}