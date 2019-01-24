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
     * @return \O2System\Reactor\Models\Sql\DataObjects\Result|bool
     */
    public function getResult()
    {
        if ($this->map->referenceModel->row instanceof Sql\DataObjects\Result\Row) {

            $criteria = $this->map->referenceModel->row->offsetGet($this->map->referenceModel->primaryKey);
            $conditions = [$this->map->relationForeignKey => $criteria];

            if ($this->map->relationModel instanceof Sql\Model) {
                $result = $this->map->relationModel->qb
                    ->from($this->map->relationTable)
                    ->getWhere($conditions);

                if ($result instanceof Result) {
                    if ($result->count() > 0) {
                        return $this->map->relationModel->result = new Sql\DataObjects\Result($result,
                            $this->map->relationModel);
                    }
                }
            } elseif ( ! empty($this->map->relationTable)) {
                $result = $this->map->referenceModel->qb
                    ->from($this->map->relationTable)
                    ->getWhere($conditions);

                if ($result instanceof Result) {
                    if ($result->count() > 0) {
                        return new Sql\DataObjects\Result($result, $this->map->referenceModel);
                    }
                }
            }
        }

        return false;
    }
}