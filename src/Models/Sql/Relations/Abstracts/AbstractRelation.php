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

namespace O2System\Reactor\Models\Sql\Relations\Abstracts;

// ------------------------------------------------------------------------

use O2System\Reactor\Models\Sql\DataObjects;
use O2System\Reactor\Models\Sql\Relations;

/**
 * Class AbstractRelations
 *
 * @package O2System\Reactor\Models\Abstracts
 */
abstract class AbstractRelation
{
    /**
     * Relations Map
     *
     * @var Relations\Maps\Reference|Relations\Maps\Associate|Relations\Maps\Inverse|\O2System\Reactor\Models\Sql\Relations\Maps\Polymorphic
     */
    protected $map;

    /**
     * Relations::__construct
     *
     * @param Relations\Maps\Reference|Relations\Maps\Associate|Relations\Maps\Inverse|\O2System\Reactor\Models\Sql\Relations\Maps\Polymorphic $map
     */
    public function __construct($map)
    {
        $this->map = $map;
    }

    // ------------------------------------------------------------------------

    /**
     * Get Result
     *
     * @return DataObjects\Result\Row|array|bool
     */
    abstract public function getResult();
}