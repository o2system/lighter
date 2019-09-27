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

namespace O2System\Reactor\Models\Files;

// ------------------------------------------------------------------------

use O2System\Filesystem\Files\JsonFile;
use O2System\Filesystem\Files\XmlFile;
use O2System\Reactor\Models\Files\Traits\FinderTrait;
use O2System\Spl\Patterns\Structural\Repository\AbstractRepository;

/**
 * Class Model
 * @package O2System\Reactor\Models\Files
 */
class Model extends AbstractRepository
{
    use FinderTrait;

    public $file;
    public $result;
    public $primaryKey = 'id';

    public function __construct()
    {
        if ( ! empty($this->file)) {
            $extension = pathinfo($this->file, PATHINFO_EXTENSION);

            switch ($extension) {
                case 'json':
                    $jsonFile = new JsonFile($this->file);
                    $this->storage = $jsonFile->readFile()->getArrayCopy();
                    break;
                case 'xml':
                    $xmlFile = new XmlFile($this->file);
                    $this->storage = $xmlFile->readFile()->getArrayCopy();
                    break;
            }

            $first = reset($this->storage);
            if ( ! isset($first[ $this->primaryKey ])) {
                $keys = $first->getKeys();
                $this->primaryKey = reset($keys);
            }
        }
    }
}