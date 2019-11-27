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

/**
 * Class ReferenceTo
 * @package O2System\Reactor\Models\Sql\Relations
 */
class ReferenceTo extends Abstracts\AbstractRelation
{
    /**
     * ReferenceTo::getResult
     *
     * @return array|bool|\O2System\Reactor\Models\Sql\DataObjects\Result\Row
     */
    public function getResult()
    {
        if ($this->map->currentModel->row instanceof Sql\DataObjects\Result\Row) {
            $criteria = $this->map->currentModel->row->offsetGet($this->map->currentForeignKey);
            $field = $this->map->referenceTable . '.' . $this->map->referencePrimaryKey;

            $this->map->referenceModel->result = null;
            $this->map->referenceModel->row = null;

            if ($result = $this->map->referenceModel->find($criteria, $field, 1)) {
                if($result instanceof Sql\DataObjects\Result\Row) {
                    return $result;
                } elseif($result instanceof Sql\DataObjects\Result) {
                    return $result->first();
                }
            }
        }

        return false;
    }
}