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

class ObjectType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var string
     *
     * Attribute name="id" type="xsd:string" use="required"
     */
    private $id = null;

    /**
     * @var string
     *
     * Attribute name="name" type="xsd:string" use="required"
     */
    private $name = null;

    /**
     * Factory method, set id and name
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
     * @param array $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null ) : bool
    {
        $local = [];
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
     * @return null|string
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
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ObjectType
     */
    public function setName( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }
}
