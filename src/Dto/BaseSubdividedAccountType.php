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
use TypeError;

use function get_called_class;

abstract class BaseSubdividedAccountType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var string[]
     *
     * The account (or accounts) that this subdivided account specifies
     * Attribute name="accountId" type="sie:AccountNumber"
     * <xsd:restriction base="xsd:string">
     *   <xsd:pattern value="[0-9]+"/>
     * </xsd:restriction>
     */
    protected $secondaryAccountRef = [];

    /**
     * @var string
     *
     * Attribute name="primaryAccountId" type="sie:AccountNumber" use="required"
     * <xsd:restriction base="xsd:string">
     *   <xsd:pattern value="[0-9]+"/>
     * </xsd:restriction>
     * Subordinate account identifier. The primary account of the subdivided account.
     */
    protected $primaryAccountId = null;

    /**
     * @var string
     *
     * Attribute name="name" type="xsd:string" use="optional"
     * Name of the subdivided account or account group.
     * If omitted the acount name of the referenced account may be used.
     */
    protected $name = null;

    /**
     * Factory method, set primaryAccountId
     *
     * @param string $primaryAccountId
     * @return static
     */
    public static function factoryPrimaryAccountId( string $primaryAccountId ) : self
    {
        $class    = get_called_class();
        $instance = new $class();
        return $instance->setPrimaryAccountId( $primaryAccountId );
    }

    /**
     * Add single secondaryAccountRef
     *
     * @param string $secondaryAccountRef
     * @return static
     * @throws InvalidArgumentException
     */
    public function addSecondaryAccountRef( string $secondaryAccountRef ) : self
    {
        $this->secondaryAccountRef[] = CommonFactory::assertAccountNumber( $secondaryAccountRef );
        return $this;
    }

    /**
     * @return string[]
     */
    public function getSecondaryAccountRef() : array
    {
        return $this->secondaryAccountRef;
    }

    /**
     * Set secondaryAccountRef's, array
     *
     * @param array $secondaryAccountRef
     * @return static
     * @throws TypeError
     */
    public function setSecondaryAccountRef( array $secondaryAccountRef ) : self
    {
        foreach( $secondaryAccountRef as $accountNumber ) {
            $this->addSecondaryAccountRef( $accountNumber );
        }
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPrimaryAccountId()
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
    public function getName()
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
