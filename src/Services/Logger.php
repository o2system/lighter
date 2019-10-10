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

namespace O2System\Reactor\Services;

// ------------------------------------------------------------------------

use O2System\Kernel\DataStructures\Config;

/**
 * Class Logger
 * @package O2System\Reactor\Services
 */
class Logger extends \O2System\Kernel\Services\Logger
{
    /**
     * Logger::__construct
     */
    public function __construct()
    {
        $config = config()->get('logger');
        $config[ 'path' ] = PATH_CACHE . 'log' . DIRECTORY_SEPARATOR;

        parent::__construct(new Config($config));
    }
}