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

class CompanyType extends Sie5DtoExtAttrBase
{
    /**
     * @var string
     *
     * Attribute name="organizationId" type="xsd:string" use="required"
     * Organization identifier. In Sweden the assigned "Organisationsnummer/personnummer" must be used.
     * För organisationer som verkligen saknar organisationsnummer, t ex syföreningar, anges "000000-0000"
     */
    private $organizationId = null;

    /**
     * @var int
     *
     * Attribute name="multiple" default="1" type="xsd:int"
     * A serial number if more than one company exists with the same organization identifier,
     * which can be the case when many sole propietorships are run seperately by the same proprietor.
     */
    private $multiple = 1;

    /**
     * @var int
     */
    public static $multipleDefault = 1;

    /**
     * @var string
     *
     * Attribute name="name" type="xsd:string" use="required"
     * Name of the company/organization
     */
    private $name = null;

    /**
     * @var string
     *
     * Attribute name="clientId" type="xsd:string"
     * ???: Bör vara mandatory i en totalfil från ett redovisningssystem, men optional vid en "importfil"
     */
    private $clientId = null;

    /**
     * Factory method, set organizationId and name, both required
     *
     * @param string $organizationId
     * @param string $name
     * @return static
     */
    public static function factoryOrganizationIdName( string $organizationId, string $name ) : self
    {
        return self::factory()
            ->setOrganizationId( $organizationId )
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
        if( empty( $this->organizationId )) {
            $local[] = self::errMissing(self::class, self::ORGANIZATIONID );
        }
        if( empty( $this->name )) {
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
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @param string $organizationId
     * @return CompanyType
     */
    public function setOrganizationId( string $organizationId ) : self
    {
        $this->organizationId = $organizationId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMultiple()
    {
        return ( $this->multiple != self::$multipleDefault ) ? $this->multiple : null;
    }

    /**
     * @param mixed $multiple
     * @return CompanyType
     */
    public function setMultiple( $multiple ) : self
    {
        $this->multiple = (int) $multiple;
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
     * @return CompanyType
     */
    public function setName( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     * @return CompanyType
     */
    public function setClientId( string $clientId ) : self
    {
        $this->clientId = $clientId;
        return $this;
    }
}
