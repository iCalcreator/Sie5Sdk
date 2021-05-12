<?php
/**
 * SieSdk     PHP SDK for Sie5 export/import format
 *            based on the Sie5 (http://www.sie.se/sie5.xsd) schema
 *
 * This file is a part of Sie5Sdk.
 *
 * @author    Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @copyright 2019-2021 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      https://kigkonsult.se
 * @version   1.0
 * @license   Subject matter of licence is the software Sie5Sdk.
 *            The above copyright, link, package and version notices,
 *            this licence notice shall be included in all copies or substantial
 *            portions of the Sie5Sdk.
 *
 *            Sie5Sdk is free software: you can redistribute it and/or modify
 *            it under the terms of the GNU Lesser General Public License as
 *            published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *            Sie5Sdk is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *            GNU Lesser General Public License for more details.
 *
 *            You should have received a copy of the GNU Lesser General Public License
 *            along with Sie5Sdk. If not, see <https://www.gnu.org/licenses/>.
 */
declare( strict_types = 1 );
namespace Kigkonsult\Sie5Sdk\Dto;

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

use function array_keys;
use function gettype;
use function sprintf;

class DimensionTypeEntry extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var ObjectType[]
     *
     * minOccurs="0" maxOccurs="unbounded"
     */
    private $object = [];

    /**
     * @var int
     *
     * Attribute name="id" type="xsd:positiveInteger" use="required"
     */
    private $id = null;

    /**
     * @var string
     *
     * Attribute name="name" type="xsd:string" use="optional"
     */
    private $name = null;

    /**
     * Factory method, set id and name
     *
     * @param mixed  $id
     * @param string $name
     * @return static
     * @throws InvalidArgumentException
     */
    public static function factoryIdName( $id, string $name = null ) : self
    {
        $instance = self::factory()
                   ->setId( $id );
        if( ! empty( $name )) {
            $instance->setName( $name );
        }
        return $instance;
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) : bool
    {
        $local = [];
        if( ! empty( $this->object )) {
            foreach( array_keys( $this->object ) as $ix1 ) { // element ix
                $inside = [];
                if( ! $this->object[$ix1]->isValid( $inside ) ) {
                    $local[self::OBJECT][$ix1] = $inside;
                }
            } // end foreach
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
    public function addObject( ObjectType $object ) : self
    {
        $this->object[] = $object;
        return $this;
    }

    /**
     * @return array
     */
    public function getObject() : array
    {
        return $this->object;
    }

    /**
     * Return array ObjectIds
     *
     * @return array
     */
    public function getAllObjectIds() : array
    {
        $objectIds = [];
        foreach( array_keys( $this->object ) as $ix ) {
            $objectIds[$ix] = $this->object[$ix]->getId();
        } // end foreach
        asort( $objectIds );
        return $objectIds;
    }

    /**
     * Return int index if Object id is set or bool true
     *
     * @param string $id
     * @return int|bool  ObjectType index or true if not found
     */
    public function isObjectIdUnique( string $id )
    {
        $hitIx = array_search( $id, $this->getAllObjectIds());
        return ( false !== $hitIx ) ? $hitIx : true;
    }

    /**
     * @param ObjectType[] $objects
     * @return static
     * @throws InvalidArgumentException
     */
    public function setObject( array $objects ) : self
    {
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
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return static
     * @throws InvalidArgumentException
     */
    public function setId( $id ) : self
    {
        $this->id = CommonFactory::assertPositiveInteger( $id );
        return $this;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }
}
