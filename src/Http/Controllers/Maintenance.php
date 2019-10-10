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
 * Class Maintenance
 *
 * @package O2System\Reactor\Http\Controllers
 */
class Maintenance extends Controller
{
    /**
     * Maintenance::$inherited
     *
     * Controller inherited flag.
     *
     * @var bool
     */
    static public $inherited = true;

    // ------------------------------------------------------------------------

    /**
     * Maintenance::index
     */
    public function index()
    {
        if (cache()->hasItem('maintenance')) {
            $maintenanceInfo = cache()->getItem('maintenance')->get();
            $this->sendError(503, $maintenanceInfo['message']);
        }
    }
}