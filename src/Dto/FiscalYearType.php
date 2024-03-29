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

use DateTime;
use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

class FiscalYearType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var string|null
     *
     * Attribute name="start" type="xsd:gYearMonth" use="required"
     * Fiscal year start date in ISO 8601 format.
     * This is also the unique identifier for the fiscal year,
     * and is used as a key for references
     * when opening and closing balances are presented in other parts of the file
     */
    private ?string $start = null;

    /**
     * @var string|null
     *
     * Attribute name="end" type="xsd:gYearMonth" use="required"
     * Fiscal year end date in ISO 8601 format
     */
    private ?string $end = null;

    /**
     * @var bool
     *
     * Attribute name="primary" default="false" type="xsd:boolean"
     * Indicates that this is the primary fiscal year in the SIE file. Exactly one year must be marked as primary.
     */
    private bool $primary = false;

    /**
     * @var bool
     */
    public static bool $primaryDefault = false;

    /**
     * @var bool
     *
     * Attribute name="closed" default="false" type="xsd:boolean"
     * "true" if fiscal year is closed, otherwise "false"
     */
    private bool $closed = false;

    /**
     * @var bool
     */
    public static bool $closedDefault = false;

    /**
     * @var bool
     *
     * Attribute name="hasLedgerEntries" type="xsd:boolean"
     */
    private ?bool $hasLedgerEntries = null;

    /**
     * @var bool
     *
     * Attribute name="hasSubordinateAccounts" type="xsd:boolean"
     */
    private ?bool $hasSubordinateAccounts = null;

    /**
     * @var bool
     *
     * Attribute name="hasAttachedVoucherFiles" type="xsd:boolean"
     */
    private ?bool $hasAttachedVoucherFiles = null;

    /**
     * @var DateTime|null
     *
     * Attribute name="lastCoveredDate" type="xsd:date" use="optional"
     * The last date on the fiscal yeare where all transactions are available.
     */
    private ?DateTime $lastCoveredDate = null;

    /**
     * Factory method, set start and end
     *
     * @param string $start
     * @param string $end
     * @return static
     */
    public static function factoryStartEnd( string $start, string $end ) : self
    {
        return self::factory()
            ->setStart( $start )
            ->setEnd( $end );
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( null === $this->start ) {
            $local[] = self::errMissing(self::class, self::START );
        }
        if( null === $this->end ) {
            $local[] = self::errMissing(self::class, self::END );
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
    public function getStart() : ?string
    {
        return $this->start;
    }

    /**
     * @param string $start
     * @return FiscalYearType
     */
    public function setStart( string $start ) : self
    {
        $this->start = CommonFactory::assertGYearMonth( $start );
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEnd() : ?string
    {
        return $this->end;
    }

    /**
     * @param string $end
     * @return FiscalYearType
     */
    public function setEnd( string $end ) : self
    {
        $this->end = CommonFactory::assertGYearMonth( $end );
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPrimary() : ?bool
    {
        return ( $this->primary !== self::$primaryDefault ) ? $this->primary : null;
    }

    /**
     * @param mixed $primary
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setPrimary( mixed $primary ) : self
    {
        $this->primary = CommonFactory::assertBoolean( $primary );
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getClosed() : ?bool
    {
        return ( $this->closed !== self::$closedDefault ) ? $this->closed : null;
    }

    /**
     * @param mixed $closed
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setClosed( mixed $closed ) : self
    {
        $this->closed  = CommonFactory::assertBoolean( $closed );
        return $this;
    }

    /**
     * @return null|bool
     */
    public function getHasLedgerEntries() : ?bool
    {
        return $this->hasLedgerEntries;
    }

    /**
     * @param mixed $hasLedgerEntries
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setHasLedgerEntries( mixed $hasLedgerEntries ) : self
    {
        $this->hasLedgerEntries = CommonFactory::assertBoolean( $hasLedgerEntries );
        return $this;
    }

    /**
     * @return null|bool
     */
    public function getHasSubordinateAccounts() : ?bool
    {
        return $this->hasSubordinateAccounts;
    }

    /**
     * @param mixed $hasSubordinateAccounts
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setHasSubordinateAccounts( mixed $hasSubordinateAccounts ) : self
    {
        $this->hasSubordinateAccounts = CommonFactory::assertBoolean( $hasSubordinateAccounts );
        return $this;
    }

    /**
     * @return null|bool
     */
    public function getHasAttachedVoucherFiles() : ?bool
    {
        return $this->hasAttachedVoucherFiles;
    }

    /**
     * @param mixed $hasAttachedVoucherFiles
     * @return FiscalYearType
     * @throws InvalidArgumentException
     */
    public function setHasAttachedVoucherFiles( mixed $hasAttachedVoucherFiles ) : self
    {
        $this->hasAttachedVoucherFiles = CommonFactory::assertBoolean( $hasAttachedVoucherFiles );
        return $this;
    }

    /**
     * @return null|DateTime
     */
    public function getLastCoveredDate() : ?DateTime
    {
        return $this->lastCoveredDate;
    }

    /**
     * @param DateTime $lastCoveredDate
     * @return FiscalYearType
     */
    public function setLastCoveredDate( DateTime $lastCoveredDate ) : self
    {
        $this->lastCoveredDate = $lastCoveredDate;
        return $this;
    }
}
