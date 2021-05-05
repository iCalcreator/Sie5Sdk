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

use function array_keys;
use function array_merge;
use function array_unique;
use function gettype;
use Kigkonsult\Sie5Sdk\Impl\SortFactory;
use function sort;
use function sprintf;
use function usort;

class JournalTypeEntry extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var JournalEntryTypeEntry[]
     *
     * minOccurs="0" maxOccurs="unbounded"
     * Journal entry
     */
    private $journalEntry = [];

    /**
     * @var string
     *
     * Attribute name="id" type="xsd:string" use="optional"
     */
    private $id = null;

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) : bool
    {
        $local = [];
        if( empty( $this->journalEntry )) {
            $local[self::JOURNALENTRY] = false;
        }
        else {
            foreach( array_keys( $this->journalEntry ) as $ix ) { // element ix
                $inside = [];
                if( ! $this->journalEntry[$ix]->isValid( $inside )) {
                    $local[self::JOURNAL][$ix] = $inside;
                }
            } // end foreach
        }
        if( ! empty( $local )) {
            $expected[self::JOURNAL] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param JournalEntryTypeEntry $journalEntry
     * @return static
     */
    public function addJournalEntry( JournalEntryTypeEntry $journalEntry ) : self
    {
        $this->journalEntry[] = $journalEntry;
        $this->sortJournalEntryOnId();
        return $this;
    }

    /**
     * @return JournalEntryTypeEntry[]
     */
    public function getJournalEntry() : array
    {
        return $this->journalEntry;
    }

    /**
     * Return array with all journalEntry ledgerEntry AccountsIds
     *
     * @return array
     */
    public function getAllJournalEntryLedgerEntryAccountIds() : array
    {
        $accountIds = [];
        foreach( array_keys( $this->journalEntry ) as $ix ) {
            $accountIds = array_merge( $accountIds, $this->journalEntry[$ix]->getAllLedgerEntryAccountIds());
        } // end foreach
        sort( $accountIds );
        return array_unique( $accountIds );
    }

    /**
     * Return array with all journalEntry VoucherReference dokumentIds
     *
     * @return array
     */
    public function getAllJournalEntryVoucherReferenceDocumentIds() : array
    {
        $documentIds = [];
        foreach( array_keys( $this->journalEntry ) as $ix ) {
            $documentIds[$ix] = $this->journalEntry[$ix]->getAllVoucherReferenceDocumentIds();
        } // end foreach
        return $documentIds;
    }

    /**
     * Return bool true if sum of each journalEntry ledgerEntries amount is zero
     *
     * @param array $errorIx
     * @return bool (on error index of journalEntry)
     */
    public function hasBalancedJournalEntryLedgerEntries( & $errorIx = [] ) : bool
    {
        foreach( array_keys( $this->journalEntry ) as $ix ) {
            if( ! $this->journalEntry[$ix]->hasBalancedLedgerEntries()) {
                $errorIx[] = $ix;
            }
        } // end foreach
        return ( empty( $errorIx ));
    }

    /**
     * Sort JournalEntry on journalEntryTypeEntry id
     */
    public function sortJournalEntryOnId()
    {
        usort( $this->journalEntry, SortFactory::$journalEntryTypeEntrySorter );
    }

    /**
     * @param array $journalEntry JournalEntryTypeEntry[]
     * @return static
     * @throws InvalidArgumentException
     */
    public function setJournalEntry( array $journalEntry ) : self
    {
        foreach( $journalEntry as $ix => $value ) {
            if( $value instanceof JournalEntryTypeEntry ) {
                $this->journalEntry[] = $value;
                continue;
            }
            $type = gettype( $value );
            if( self::$OBJECT == $type ) {
                $type = get_class( $value );
            }
            throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::JOURNALENTRY, $ix, $type ));
        } // end foreach
        $this->sortJournalEntryOnId();
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return static
     */
    public function setId( $id ) : self
    {
        $this->id = (string) $id;
        return $this;
    }
}
