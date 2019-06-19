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
namespace Kigkonsult\Sie5Sdk\Impl;

use Kigkonsult\Sie5Sdk\Dto\FiscalYearType;
use Kigkonsult\Sie5Sdk\Dto\JournalEntryType;
use Kigkonsult\Sie5Sdk\Dto\JournalEntryTypeEntry;
use Kigkonsult\Sie5Sdk\Dto\JournalType;
use Kigkonsult\Sie5Sdk\Dto\JournalTypeEntry;

class SortFactory
{
    /**
     * @var callable
     * @access private
     * @static
     * @usedby FiscalYearsType
     */
    public static $fiscalYearTypeSorter = [ __CLASS__, 'fiscalYearTypeSortOnStart' ];

    /**
     * @var callable
     * @static
     * @usedby JournalType
     */
    public static $journalEntryTypeSorter = [ __CLASS__, 'journalEntryTypeSortOnId' ];

    /**
     * @var callable
     * @access private
     * @static
     * @usedby JournalTypeEntry
     */
    public static $journalEntryTypeEntrySorter = [ __CLASS__, 'journalEntryTypeEntrySortOnId' ];

    /**
     * @var callable
     * @access private
     * @static
     * @usedby Sie
     */
    public static $journalTypeSorter = [ __CLASS__, 'journalTypeSortOnId' ];

    /**
     * @var callable
     * @usedby SieEntry
     */
    public static $journalTypeEntrySorter = [ __CLASS__, 'journalTypeEntrySortOnId' ];

    /**
     * Sort JournalEntriesTypes on id and journalDate
     *
     * @param JournalEntryType $a
     * @param JournalEntryType $b
     * @return int
     */
    public static function journalEntryTypeSortOnId( JournalEntryType $a, JournalEntryType $b ) {
        $aId = $a->getId();
        $bId = $b->getId();
        if( $aId < $bId ) {
            return -1;
        }
        if( $aId > $bId ) {
            return 1;
        }
        $aJournalDate = $a->getJournalDate();
        $bJournalDate = $b->getJournalDate();
        if( $aJournalDate < $bJournalDate ) {
            return -1;
        }
        if( $aJournalDate > $bJournalDate ) {
            return 1;
        }
        return 0;
    }

    /**
     * Sort JournalEntryTypeEntries on id and journalDate
     *
     * @param JournalEntryTypeEntry $a
     * @param JournalEntryTypeEntry $b
     * @return int
     */
    public static function journalEntryTypeEntrySortOnId( JournalEntryTypeEntry $a, JournalEntryTypeEntry $b ) {
        $aId = $a->getId();
        $bId = $b->getId();
        if( $aId < $bId ) {
            return -1;
        }
        if( $aId > $bId ) {
            return 1;
        }
        $aJournalDate = $a->getJournalDate();
        $bJournalDate = $b->getJournalDate();
        if( $aJournalDate < $bJournalDate ) {
            return -1;
        }
        if( $aJournalDate > $bJournalDate ) {
            return 1;
        }
        return 0;
    }

    /**
     * Sort fiscalYears on Start
     *
     * @param FiscalYearType $a
     * @param FiscalYearType $b
     * @return int
     */
    public static function fiscalYearTypeSortOnStart( FiscalYearType $a, FiscalYearType $b ) {
        $aStart = $a->getStart();
        $bStart = $b->getStart();
        if( $aStart < $bStart ) {
            return -1;
        }
        if( $aStart > $bStart ) {
            return 1;
        }
        return 0;
    }

    /**
     * Sort JournalTypes on id
     *
     * @param JournalType $a
     * @param JournalType $b
     * @return int
     */
    public static function journalTypeSortOnId( JournalType $a, JournalType $b ) {
        $aId = $a->getId();
        $bId = $b->getId();
        if( $aId < $bId ) {
            return -1;
        }
        if( $aId > $bId ) {
            return 1;
        }
        return 0;
    }

    /**
     * Sort JournalTypeEntries on id
     *
     * @param JournalTypeEntry $a
     * @param JournalTypeEntry $b
     * @return int
     */
    public static function journalTypeEntrySortOnId( JournalTypeEntry $a, JournalTypeEntry $b ) {
        $aId = $a->getId();
        $bId = $b->getId();
        if( $aId < $bId ) {
            return -1;
        }
        if( $aId > $bId ) {
            return 1;
        }
        return 0;
    }
}