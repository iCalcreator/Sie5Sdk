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

use TypeError;

use function array_keys;

class AccountAggregationType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var TagType[]
     *          minOccurs="1" maxOccurs="unbounded"
     */
    private array $tag = [];

    /**
     * @var string|null
     *            Attribute name="id" type="xsd:string" use="required"
     */
    private ?string $id = null;

    /**
     * @var string|null
     *            Attribute name="name" type="xsd:string" use="required"
     */
    private ?string $name = null;

    /**
     * @var string|null
     *            Attribute name="taxonomy" type="xsd:string" use="optional"
     */
    private ?string $taxonomy = null;

    /**
     * Factory method, set id, name and type
     *
     * @param string $id
     * @param string $name
     * @return static
     */
    public static function factoryIdName( string $id, string $name ) : self
    {
        return self::factory()
            ->setId( $id )
            ->setName( $name );
    }

    /**
     * Return bool true is instance is valid
     *
     * @param null|array $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( empty( $this->tag )) {
            $local[] = self::errMissing( self::class, self::TAG );
        }
        else {
            $inside = [];
            foreach( array_keys( $this->tag ) as $ix ) {
                $inside[$ix] = [];
                if( $this->tag[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::TAG );
                $local[$key] = $inside;
            } // end if
        } // end if
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
     * Add single TagType
     *
     * @param TagType $tag
     * @return static
     */
    public function addTag( TagType $tag ) : self
    {
        $this->tag[] = $tag;
        return $this;
    }

    /**
     * @return TagType[]
     */
    public function getTag() : array
    {
        return $this->tag;
    }

    /**
     * Set TagTypes. array
     *
     * @param TagType[] $tag
     * @return static
     * @throws TypeError
     */
    public function setTag( array $tag ) : self
    {
        foreach( $tag as $value) {
            $this->addTag( $value );
        }
        return $this;
    }

    /**
     * @return null|string
     */
    public function getId() : ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     */
    public function setId( string $id ) : self
    {
        $this->id = $id;
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

    /**
     * @return string
     */
    public function getTaxonomy() : ?string
    {
        return $this->taxonomy;
    }

    /**
     * @param string $taxonomy
     * @return static
     */
    public function setTaxonomy( string $taxonomy ) : self
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }
}
