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

abstract class BaseSubdividedAccountTypeEntry extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var string|null
     *
     * Attribute name="primaryAccountId" type="sie:AccountNumber" use="required"
     * <xsd:restriction base="xsd:string">
     *   <xsd:pattern value="[0-9]+"/>
     * </xsd:restriction>
     * Subordinate account identifier. The primary account of the subdivided account.
     */
    protected ?string $primaryAccountId = null;

    /**
     * @var string|null
     *
     * Attribute name="name" type="xsd:string" use="optional"
     * Name of the subdivided account or account group.
     * If omitted the account name of the referenced account may be used.
     */
    protected ?string $name = null;

    /**
     * Factory method, set primaryAccountId
     *
     * @param string $primaryAccountId
     * @return static
     */
    public static function factoryPrimaryAccountId( string $primaryAccountId ) : static
    {
        $class    = static::class;
        $instance = new $class();
        return $instance->setPrimaryAccountId( $primaryAccountId );
    }

    /**
     * @return null|string
     */
    public function getPrimaryAccountId() : ?string
    {
        return $this->primaryAccountId;
    }

    /**
     * @param string $primaryAccountId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setPrimaryAccountId( string $primaryAccountId ) : self
    {
        $this->primaryAccountId = CommonFactory::assertAccountNumber( $primaryAccountId );
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
     * @throws InvalidArgumentException
     */
    public function setName( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }
}
