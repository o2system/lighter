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

namespace O2System\Reactor\Models\Sql\Relations;

// ------------------------------------------------------------------------

use O2System\Database\DataObjects\Result;
use O2System\Reactor\Models\Sql;

/**
 * Class HasMany
 *
 * @package O2System\Reactor\Models\Sql\Relations
 */
class HasMany extends Sql\Relations\Abstracts\AbstractRelation
{
    /**
     * HasMany::getResult
     * 
     * @return array|bool|Result
     */
    public function getResult()
    {
        if ($this->map->currentModel->row instanceof Sql\DataObjects\Result\Row) {
            $criteria = $this->map->currentModel->row->offsetGet($this->map->currentPrimaryKey);
            $condition = [
                $this->map->referenceTable . '.' . $this->map->referenceForeignKey => $criteria,
            ];

            if ($result = $this->map->referenceModel->findWhere($condition)) {
                return $result;
            }
        }

        return new Result([]);
    }
}