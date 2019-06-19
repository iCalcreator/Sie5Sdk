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
use function is_null;
use function sprintf;

class AccountAggregationType extends Sie5DtoBase implements Sie5DtoInterface
{


    /**
     * @var array  [ *TagType ]
     *          minOccurs="1" maxOccurs="unbounded"
     * @access private
     */
    private $tag = [];

    /**
     * @var string
     *            attribute name="id" type="xsd:string" use="required"
     * @access private
     */
    private $id = null;

    /**
     * @var string
     *            attribute name="name" type="xsd:string" use="required"
     * @access private
     */
    private $name = null;

    /**
     * @var string
     *            attribute name="taxonomy" type="xsd:string" use="optional"
     * @access private
     */
    private $taxonomy = null;



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->tag )) {
            $local[self::TAG] = self::TAG;
        }
        else {
            foreach( array_keys( $this->tag ) as $ix ) {
                $inside = [];
                if( ! $this->tag[$ix]->isValid( $inside )) {
                    $local[self::TAG][$ix] = $inside;
                }
            }
        }
        if( is_null( $this->id )) {
            $local[self::ID] = false;
        }
        if( empty( $this->name )) {
            $local[self::NAME] = false;
        }
        if( ! empty( $local )) {
            $expected[self::ACCOUNTAGGREGATION] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param TagType $tag
     * @return static
     */
    public function addTag( TagType $tag ) {
        $this->tag[] = $tag;
        return $this;
    }

    /**
     * @return array
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * @param array $tag   *TagType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setTag( array $tag ) {
        foreach( $tag as $ix => $value) {
            if( $value instanceof TagType ) {
                $this->tag[$ix] = $value;
            }
            else {
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::ACCOUNTAGGREGATION, $ix, self::TAG ));
            }
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     * @throws InvalidArgumentException
     */
    public function setId( $id ) {
        $this->id = CommonFactory::assertString( $id );
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

    /**
     * @return string
     */
    public function getTaxonomy() {
        return $this->taxonomy;
    }

    /**
     * @param string $taxonomy
     * @return static
     * @throws InvalidArgumentException
     */
    public function setTaxonomy( $taxonomy ) {
        $this->taxonomy = CommonFactory::assertString( $taxonomy );
        return $this;
    }

}