<?php
/**
 * Sie5Sdk    PHP SDK for Sie5 export/import format
 *            based on the Sie5 (http://www.sie.se/sie5.xsd) schema
 *
 * This file is a part of Sie5Sdk.
 *
 * @author    Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @copyright 2019-2021 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      https://kigkonsult.se
 * @license   Subject matter of licence is the software Sie5Sdk.
 *            The above copyright, link and package notices, this licence
 *            notice shall be included in all copies or substantial portions
 *            of the Sie5Sdk.
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
use TypeError;

use function array_keys;
use function sprintf;

class DimensionType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var ObjectType[]
     *
     * minOccurs="0" maxOccurs="unbounded"
     */
    private array $object = [];

    /**
     * @var int|null
     *
     * Attribute name="id" type="xsd:positiveInteger" use="required"
     */
    private ?int $id = null;

    /**
     * @var string|null
     *
     * Attribute name="name" type="xsd:string" use="required"
     */
    private ?string $name = null;

    /**
     * Factory method, set id and name
     *
     * @param mixed $id
     * @param string $name
     * @return static
     */
    public static function factoryIdName( mixed $id, string $name ) : self
    {
        return self::factory()
                   ->setId( $id )
                   ->setName( $name );
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( ! empty( $this->object )) {
            $inside = [];
            foreach( array_keys( $this->object ) as $ix ) { // element ix
                $inside[$ix] = [];
                if( $this->object[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::OBJECT );
                $local[$key] = $inside;
            } // end if
        }
        if( null === $this->id ) {
            $local[] = self::errMissing(self::class, self::ID );
        }
        if( null === $this->name ) {
            $local[] = self::errMissing(self::class, self::NAME );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single ObjectType
     *
     * @param ObjectType $object
     * @return static
     */
    public function addObject( ObjectType $object ) : self
    {
        if( $object->isValid() &&
            ( true !== $this->isObjectIdUnique( $object->getId()))) {
            throw new InvalidArgumentException(
                sprintf( self::$FMTERR11, self::OBJECT, self::ID, $object->getId())
            );
        } // end if
        $this->object[] = $object;
        return $this;
    }

    /**
     * @return ObjectType[]
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
        }
        asort( $objectIds );
        return $objectIds;
    }

    /**
     * Return int index if Object id is set or bool true if not
     *
     * @param string $id
     * @return bool|int|string  ObjectType index or true if not found
     */
    public function isObjectIdUnique( string $id ) : bool | int | string
    {
        $hitIx = array_search( $id, $this->getAllObjectIds(), true );
        return ( false !== $hitIx ) ? $hitIx : true;
    }

    /**
     * Set ObjectTypes, array
     *
     * @param ObjectType[] $objects
     * @return static
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function setObject( array $objects ) : self
    {
        foreach( $objects as $object ) {
            $this->addObject( $object );
        } // end foreach
        return $this;
    }

    /**
     * @return null|int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return static
     * @throws InvalidArgumentException
     */
    public function setId( mixed $id ) : self
    {
        $this->id = CommonFactory::assertPositiveInteger( $id );
        return $this;
    }

    /**
     * @return null|string
     */
    public function getName() : ?string
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
