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

abstract class BaseSubdividedAccountType extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var int[]
     *              The account (or accounts) that this subdivided account specifies
     *
     *              attribute name="accountId" type="sie:AccountNumber"
     *                  <xsd:restriction base="xsd:string">
     *                      <xsd:pattern value="[0-9]+"/>
     *                  </xsd:restriction>
     * @access protected
     */
    protected $secondaryAccountRef = [];

    /**
     * @var int
     *            attribute name="primaryAccountId" type="sie:AccountNumber" use="required"
     *                <xsd:restriction base="xsd:string">
     *                    <xsd:pattern value="[0-9]+"/>
     *                </xsd:restriction>
     *            Subordinate account identifier. The primary account of the subdivided account.
     * @access protected
     */
    protected $primaryAccountId = null;

    /**
     * @var string
     *          attribute name="name" type="xsd:string" use="optional"
     *          Name of the subdivided account or account group.
     *          If omitted the acount name of the referenced account may be used.
     * @access protected
     */
    protected $name = null;



    /**
     * @param int $secondaryAccountRef
     * @return static
     * @throws InvalidArgumentException
     */
    public function addSecondaryAccountRef( $secondaryAccountRef ) {
        $this->secondaryAccountRef[] = CommonFactory::assertAccountNumber( $secondaryAccountRef );
        return $this;
    }

    /**
     * @return int[]
     */
    public function getSecondaryAccountRef() {
        return $this->secondaryAccountRef;
    }

    /**
     * @param array $secondaryAccountRef
     * @return static
     * @throws InvalidArgumentException
     */
    public function setSecondaryAccountRef( array $secondaryAccountRef ) {
        foreach( $secondaryAccountRef as $ix => $accountNumber ) {
            $this->secondaryAccountRef[$ix] = CommonFactory::assertAccountNumber( $accountNumber, $ix );
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getPrimaryAccountId() {
        return $this->primaryAccountId;
    }

    /**
     * @param int $primaryAccountId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setPrimaryAccountId( $primaryAccountId ) {
        $this->primaryAccountId = CommonFactory::assertAccountNumber( $primaryAccountId );
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



}