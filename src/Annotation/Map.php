<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Annotation;

use CountLang\Entity\EntityMap;

/**
 * Map class that manages annotations of Entity object properties.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Annotation
 * @link      http://github.com/countlang/countlang
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class Map
{
    /**
     * Contains type annotation.
     *
     * @var string
     * @Enum({"boolean", "integer" ,"double", "string", "array"})
     */
    public $type;

    /**
     * Contains source annotation.
     *
     * @var string
     * @Required
     */
    public $source;

    /**
     * Contains isIdentifier annotation.
     *
     * @var bool
     * @Required
     */
    public $isIdentifier;

    /**
     * Contains isRequired annotation.
     *
     * @var bool
     * @Required
     */
    public $isRequired;

    /**
     * Returns array with annotation data for Entity object property.
     *
     * @param string $type
     *
     * @return array
     */
    public function getAsArray($type)
    {
        if (null !== $this->type) {
            $type = $this->type;
        }

        return [
            EntityMap::MAP_PARAM_TYPE          => $type,
            EntityMap::MAP_PARAM_SOURCE        => $this->source,
            EntityMap::MAP_PARAM_IS_IDENTIFIER => $this->isIdentifier,
            EntityMap::MAP_PARAM_IS_REQUIRED   => $this->isRequired,
        ];
    }
}