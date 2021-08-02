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

use Kigkonsult\Sie5Sdk\Impl\SortFactory;
use TypeError;

use function array_keys;
use function array_merge;
use function array_unique;
use function sort;
use function usort;

class JournalType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var JournalEntryType[]
     *
     * Attribute minOccurs="0" maxOccurs="unbounded"
     * Journal entry
     */
    private $journalEntry = [];

    /**
     * @var string
     *
     * Attribute name="id" type="xsd:string" use="required"
     * Journal identifier
     */
    private $id = null;

    /**
     * @var string
     *
     * Attribute name="name" type="xsd:string" use="required"
     * Journal name
     */
    private $name = null;

    /**
     * Return instance, set id and name
     *
     * @param string $id
     * @param string $name
     * @return static
     */
    public static function factoryIdName( string $id, string $name ) : self
    {
        return self::factory()
            ->setId( $id )
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
        if( empty( $this->journalEntry )) {
            $local[] = self::errMissing(self::class, self::JOURNALENTRY );
        }
        else {
            $inside = [];
            foreach( array_keys( $this->journalEntry ) as $ix ) { // element ix
                $inside[$ix] = [];
                if( $this->journalEntry[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::JOURNALENTRY );
                $local[$key] = $inside;
            } // end if
        } // end if
        if( null === $this->id ) {
            $local[] = self::errMissing(self::class, self::ID );
        }
        if( null === $this->name ) {
            $local[] = self::errMissing(self::class, self::NAME );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single JournalEntryType
     *
     * @param JournalEntryType $journalEntry
     * @return static
     */
    public function addJournalEntry( JournalEntryType $journalEntry ) : self
    {
        $this->journalEntry[] = $journalEntry;
        return $this;
    }

    /**
     * @return JournalEntryType[]
     */
    public function getJournalEntry() : array
    {
        $this->sortJournalEntryOnId();
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
     * Set JournalEntryTypes, array
     *
     * @param JournalEntryType[] $journalEntry
     * @return static
     * @throws TypeError
     */
    public function setJournalEntry( array $journalEntry ) : self
    {
        foreach( $journalEntry as $value ) {
            $this->addJournalEntry( $value );
        } // end foreach
        $this->sortJournalEntryOnId();
        return $this;
    }

    /**
     * Sort JournalEntry on journalEntryType id
     */
    public function sortJournalEntryOnId()
    {
        usort( $this->journalEntry, SortFactory::$journalEntryTypeSorter );
    }

    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     */
    public function setId( string $id ) : self
    {
        $this->id = $id;
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
     */
    public function setName( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }
}
