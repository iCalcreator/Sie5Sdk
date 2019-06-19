<?php
/**
 * SieSdk    PHP SDK for Sie5 export/import format
 *           based on the Sie5 (http://www.sie.se/sie5.xsd) schema
 *
 * This file is a part of Sie5Sdk.
 *
 * Copyright 2019 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * author    Kjell-Inge Gustafsson, kigkonsult
 * Link      https://kigkonsult.se
 * Version   0.95
 * License   Subject matter of licence is the software Sie5Sdk.
 *           The above copyright, link, package and version notices,
 *           this licence notice shall be included in all copies or substantial
 *           portions of the Sie5Sdk.
 *
 *           Sie5Sdk is free software: you can redistribute it and/or modify
 *           it under the terms of the GNU Lesser General Public License as published
 *           by the Free Software Foundation, either version 3 of the License,
 *           or (at your option) any later version.
 *
 *           Sie5Sdk is distributed in the hope that it will be useful,
 *           but WITHOUT ANY WARRANTY; without even the implied warranty of
 *           MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *           GNU Lesser General Public License for more details.
 *
 *           You should have received a copy of the GNU Lesser General Public License
 *           along with Sie5Sdk. If not, see <https://www.gnu.org/licenses/>.
 */
namespace Kigkonsult\Sie5Sdk\Dto;

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

use function array_keys;
use function gettype;
use function sprintf;

class DimensionTypeEntry extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var array   [ *ObjectType ]
     *               minOccurs="0" maxOccurs="unbounded"
     * @access private
     */
    private $object = [];

    /**
     * @var int
     *         attribute name="id" type="xsd:positiveInteger" use="required"
     * @access private
     */
    private $id = null;

    /**
     * @var string
     *            attribute name="name" type="xsd:string" use="optional"
     * @access private
     */
    private $name = null;



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( ! empty( $this->object )) {
            foreach( array_keys( $this->object ) as $ix1 ) { // element ix
                $inside = [];
                if( ! $this->object[$ix1]->isValid( $inside ) ) {
                    $local[self::OBJECT][$ix1] = $inside;
                }
            }
        }
        if( empty( $this->id )) {
            $local[self::ID] = false;
        }
        if( ! empty( $local )) {
            $expected[self::DIMENSION] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param ObjectType $object
     * @return static
     */
    public function addObject( ObjectType $object ) {
        $this->object[] = $object;
        return $this;
    }

    /**
     * @return array
     */
    public function getObject() {
        return $this->object;
    }

    /**
     * Return array ObjectIds
     *
     * @return array
     */
    public function getAllObjectIds() {
        $objectIds = [];
        foreach( array_keys( $this->object ) as $ix ) {
            $objectIds[$ix] = $this->object[$ix]->getId();
        }
        asort( $objectIds );
        return $objectIds;
    }

    /**
     * Return int index if Object id is set or bool true
     *
     * @param int $id
     * @return int|bool  ObjectType index or true if not found
     */
    public function isObjectIdUnique( $id ) {
        $hitIx = array_search( $id, $this->getAllObjectIds());
        return ( false !== $hitIx ) ? $hitIx : true;
    }

    /**
     * @param array $objects
     * @return static
     * @throws InvalidArgumentException
     */
    public function setObject( array $objects ) {
        foreach( $objects as $ix => $object ) {
            switch( true ) {
                case ( ! $object instanceof ObjectType ) :
                    $type = gettype( $object );
                    if( self::$OBJECT == $type ) {
                        $type = get_class( $object );
                    }
                    throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::OBJECT, $ix, $type ) );
                    break;
                case ( true !== $this->isObjectIdUnique( $object->getId())) :
                    throw new InvalidArgumentException(
                        sprintf( self::$FMTERR5, self::OBJECT, self::ID, $object->getId() )
                    );
                    break;
                default :
                    $this->object[$ix] = $object;
                    break;
            } // end switch
        } // end foreach
        return $this;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return static
     * @throws InvalidArgumentException
     */
    public function setId( $id ) {
        $this->id = CommonFactory::assertPositiveInteger( $id );
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     * @throws InvalidArgumentException
     */
    public function setName( $name ) {
        $this->name = CommonFactory::assertString( $name );
        return $this;
    }

}