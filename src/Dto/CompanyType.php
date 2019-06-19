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

class CompanyType extends Sie5DtoExtAttrBase
{

    /**
     * @var string
     *
     * attribute name="organizationId" type="xsd:string" use="required"
     * Organization identifier. In Sweden the assigned "Organisationsnummer/personnummer" must be used.
     * För organisationer som verkligen saknar organisationsnummer, t ex syföreningar, anges "000000-0000"
     * @access private
     */
    private $organizationId = null;

    /**
     * @var int
     *
     * attribute name="multiple" default="1" type="xsd:int"
     * A serial number if more than one company exists with the same organization identifier,
     * which can be the case when many sole propietorships are run seperately by the same proprietor.
     * @access private
     */
    private $multiple = 1;

    /**
     * @var int
     */
    public static $multipleDefault = 1;

    /**
     * @var string
     *
     * attribute name="name" type="xsd:string" use="required"
     * Name of the company/organization
     * @access private
     */
    private $name = null;

    /**
     * @var string
     *
     * attribute name="clientId" type="xsd:string"
     * ???: Bör vara mandatory i en totalfil från ett redovisningssystem, men optional vid en "importfil"
     * @access private
     */
    private $clientId = null;

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->organizationId )) {
            $local[self::ORGANIZATIONID] = false;
        }
        if( empty( $this->name )) {
            $local[self::NAME] = false;
        }
        if( ! empty( $local )) {
            $expected[self::COMPANY] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getOrganizationId() {
        return $this->organizationId;
    }

    /**
     * @param string $organizationId
     * @return CompanyType
     * @throws InvalidArgumentException
     */
    public function setOrganizationId( $organizationId ) {
        $this->organizationId = CommonFactory::assertString( $organizationId );
        return $this;
    }

    /**
     * @return int
     */
    public function getMultiple() {
        return ( $this->multiple != self::$multipleDefault ) ? $this->multiple : null;
    }

    /**
     * @param int $multiple
     * @return CompanyType
     * @throws InvalidArgumentException
     */
    public function setMultiple( $multiple ) {
        $this->multiple = CommonFactory::assertInt( $multiple );
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
     * @return CompanyType
     * @throws InvalidArgumentException
     */
    public function setName( $name ) {
        $this->name = CommonFactory::assertString( $name );
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId() {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     * @return CompanyType
     * @throws InvalidArgumentException
     */
    public function setClientId( $clientId ) {
        $this->clientId = CommonFactory::assertString( $clientId );
        return $this;
    }

}