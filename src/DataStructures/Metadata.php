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

namespace O2System\Reactor\DataStructures;

// ------------------------------------------------------------------------

use O2System\Spl\Patterns\Structural\Repository\AbstractRepository;

/**
 * Class Metadata
 *
 * @package O2System\Reactor\DataStructures\Commons
 */
class Metadata extends AbstractRepository
{
    /**
     * Metadata::__construct
     *
     * @param array $metadata
     */
    public function __construct(array $metadata = [])
    {
        foreach ($metadata as $name => $content) {
            if (is_array($content)) {
                $content = new self($content);
            }

            $this->store($name, $content);
        }
    }
}