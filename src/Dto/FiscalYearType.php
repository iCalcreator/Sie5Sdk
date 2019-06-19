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

use DateTime;
use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

class FiscalYearType extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var string
     *
     * attribute name="start" type="xsd:gYearMonth" use="required"
     * Fiscal year start date in ISO 8601 format.
     * This is also the unique identifier for the fiscal year,
     * and is used as a key for references
     * when opening and closing balances are presented in other parts of the file
     * @access private
     */
    private $start = null;

    /**
     * @var string
     *
     * attribute name="end" type="xsd:gYearMonth" use="required"
     * Fiscal year end date in ISO 8601 format
     * @access private
     */
    private $end = null;

    /**
     * @var bool
     *
     * attribute name="primary" default="false" type="xsd:boolean"
     * Indicates that this is the primary fiscal year in the SIE file. Exactly one year must be marked as primary.
     * @access private
     */
    private $primary = false;

    /**
     * @var bool
     */
    public static $primaryDefault = false;

    /**
     * @var bool
     *
     * attribute name="closed" default="false" type="xsd:boolean"
     * "true" if fiscal year is closed, otherwise "false"
     * @access private
     */
    private $closed = false;

    /**
     * @var bool
     */
    public static $closedDefault = false;

    /**
     * @var bool
     *
     * attribute name="hasLedgerEntries" type="xsd:boolean"
     * @access private
     */
    private $hasLedgerEntries = null;

    /**
     * @var bool
     *
     * attribute name="hasSubordinateAccounts" type="xsd:boolean"
     * @access private
     */
    private $hasSubordinateAccounts = null;

    /**
     * @var bool
     *
     * attribute name="hasAttachedVoucherFiles" type="xsd:boolean"
     * @access private
     */
    private $hasAttachedVoucherFiles = null;

    /**
     * @var DateTime
     *
     * attribute name="lastCoveredDate" type="xsd:date" use="optional"
     * The last date on the fiscal yeare where all transactions are available.
     * @access private
     */
    private $lastCoveredDate = null;



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->start )) {
            $local[self::START] = false;
        }
        if( empty( $this->end )) {
            $local[self::END] = false;
        }
        if( ! empty( $local )) {
            $expected[self::FISCALYEAR] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * @param string $start
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setStart( $start ) {
        $this->start = CommonFactory::assertGYearMonth( $start );
        return $this;
    }

    /**
     * @return string
     */
    public function getEnd() {
        return $this->end;
    }

    /**
     * @param string $end
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setEnd( $end ) {
        $this->end = CommonFactory::assertGYearMonth( $end );
        return $this;
    }

    /**
     * @return bool
     */
    public function getPrimary() {
        return ( $this->primary != self::$primaryDefault ) ? $this->primary : null;
    }

    /**
     * @param bool $primary
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setPrimary( $primary ) {
        $this->primary = CommonFactory::assertBoolean( $primary );
        return $this;
    }

    /**
     * @return bool
     */
    public function getClosed() {
        return ( $this->closed != self::$closedDefault ) ? $this->closed : null;
    }

    /**
     * @param bool $closed
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setClosed( $closed ) {
        $this->closed  = CommonFactory::assertBoolean( $closed );
        return $this;
    }

    /**
     * @return bool
     */
    public function getHasLedgerEntries() {
        return $this->hasLedgerEntries;
    }

    /**
     * @param bool $hasLedgerEntries
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setHasLedgerEntries( $hasLedgerEntries ) {
        $this->hasLedgerEntries = CommonFactory::assertBoolean( $hasLedgerEntries );
        return $this;
    }

    /**
     * @return bool
     */
    public function getHasSubordinateAccounts() {
        return $this->hasSubordinateAccounts;
    }

    /**
     * @param bool $hasSubordinateAccounts
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setHasSubordinateAccounts( $hasSubordinateAccounts ) {
        $this->hasSubordinateAccounts = CommonFactory::assertBoolean( $hasSubordinateAccounts );
        return $this;
    }

    /**
     * @return bool
     */
    public function getHasAttachedVoucherFiles() {
        return $this->hasAttachedVoucherFiles;
    }

    /**
     * @param bool $hasAttachedVoucherFiles
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setHasAttachedVoucherFiles( $hasAttachedVoucherFiles ) {
        $this->hasAttachedVoucherFiles = CommonFactory::assertBoolean( $hasAttachedVoucherFiles );
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastCoveredDate() {
        return $this->lastCoveredDate;
    }

    /**
     * @param DateTime $lastCoveredDate
     * @return FiscalYearType
     */
    public function setLastCoveredDate( DateTime $lastCoveredDate ) {
        $this->lastCoveredDate = $lastCoveredDate;
        return $this;
    }

}