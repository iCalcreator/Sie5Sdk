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

class CorrectedByType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var string|null
     *
     * Attribute name="fiscalYearId" type="xsd:gYearMonth"
     */
    private ?string $fiscalYearId = null;

    /**
     * @var string|null
     *
     * Attribute name="journalId" type="xsd:string" use="required"
     */
    private ?string $journalId = null;

    /**
     * @var int|null
     *
     * Attribute name="journalEntryId" type="xsd:nonNegativeInteger" use="required"
     */
    private ?int $journalEntryId = null;

    /**
     * Factory method, set id, name and type
     *
     * @param string $journalId
     * @param mixed $journalEntryId
     * @return static
     */
    public static function factoryJournalIdJournalEntryId( string $journalId, mixed $journalEntryId ) : self
    {
        return self::factory()
                   ->setJournalId( $journalId )
                   ->setJournalEntryId( $journalEntryId );
    }

    /**
     * Return bool true is instance is valid
     *
     * @param null|array $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( null === $this->journalId ) {
            $local[] = self::errMissing(self::class, self::JOURNALID );
        }
        if( null === $this->journalEntryId ) {
            $local[] = self::errMissing(self::class, self::JOURNALENTRYID );
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
    public function getFiscalYearId() : ?string
    {
        return $this->fiscalYearId;
    }

    /**
     * @param string $fiscalYearId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setFiscalYearId( string $fiscalYearId ) : self
    {
        $this->fiscalYearId = CommonFactory::assertGYearMonth( $fiscalYearId );
        return $this;
    }

    /**
     * @return null|string
     */
    public function getJournalId() : ?string
    {
        return $this->journalId;
    }

    /**
     * @param string $journalId
     * @return static
     */
    public function setJournalId( string $journalId ) : self
    {
        $this->journalId = $journalId;
        return $this;
    }

    /**
     * @return null|int
     */
    public function getJournalEntryId() : ?int
    {
        return $this->journalEntryId;
    }

    /**
     * @param mixed $journalEntryId
     * @return static
     */
    public function setJournalEntryId( mixed $journalEntryId ) : self
    {
        $this->journalEntryId = CommonFactory::assertNonNegativeInteger( $journalEntryId );
        return $this;
    }
}
