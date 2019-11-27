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

namespace O2System\Reactor\Models\Sql\Relations\Maps;

// ------------------------------------------------------------------------

use O2System\Reactor\Models\Sql\Model;
use O2System\Reactor\Models\Sql\Relations\Maps\Abstracts\AbstractMap;

/**
 * Class Polymorphic
 * @package O2System\Reactor\Models\Sql\Relations\Maps
 */
class Polymorphic extends AbstractMap
{
    /**
     * Polymorphic::$morphKey
     * 
     * @var string
     */
    public $morphKey = 'reference';

    // ------------------------------------------------------------------------
    
    /**
     * Polymorphic::__construct
     *
     * @param \O2System\Reactor\Models\Sql\Model        $currentModel
     * @param string|\O2System\Reactor\Models\Sql\Model $referenceModel
     * @param string|null                               $foreignKey
     * @param string|null                               $morphKey
     */
    public function __construct(
        Model $currentModel,
        $referenceModel,
        $foreignKey = null,
        $morphKey = null
    ) {
        // Mapping Models
        $this->mappingCurrentModel($currentModel);
        $this->mappingReferenceModel($referenceModel);

        // Defined Current Foreign Key
        $this->referenceForeignKey = (isset($foreignKey) ? $foreignKey
            : 'id_' . $this->currentTable);
    }
}