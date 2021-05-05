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

use function array_keys;
use function sprintf;

class AccountAggregationType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var TagType[]
     *          minOccurs="1" maxOccurs="unbounded"
     */
    private $tag = [];

    /**
     * @var string
     *            Attribute name="id" type="xsd:string" use="required"
     */
    private $id = null;

    /**
     * @var string
     *            Attribute name="name" type="xsd:string" use="required"
     */
    private $name = null;

    /**
     * @var string
     *            Attribute name="taxonomy" type="xsd:string" use="optional"
     */
    private $taxonomy = null;

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) : bool
    {
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
        if( null == $this->id ) {
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
     * @param TagType[] $tag
     * @return static
     * @throws InvalidArgumentException
     */
    public function setTag( array $tag ) : self
    {
        foreach( $tag as $ix => $value) {
            if( $value instanceof TagType ) {
                $this->tag[] = $value;
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
    public function getId()
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
     * @return string
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

    /**
     * @return string
     */
    public function getTaxonomy()
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
