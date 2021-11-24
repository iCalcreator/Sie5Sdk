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
use TypeError;

use function array_keys;
use function array_unique;
use function sort;

class JournalEntryType extends Sie5DtoExtAttrBase
{
    /**
     * @var EntryInfoType|null
     *
     * Information about how and when this record was entered (registered) into the system.
     * Applies to all subitems if not otherwise specified.
     */
    private ?EntryInfoType $entryInfo = null;

    /**
     * @var OriginalEntryInfoType|null
     *
     * minOccurs="0"
     * Information about how and when this record originally was entered (registered)
     * into some pre-system.
     */
    private ?OriginalEntryInfoType $originalEntryInfo = null;

    /**
     * Container for ledger entries
     *
     * @var LedgerEntryType[]
     *
     * Attribute  minOccurs="0" maxOccurs="unbounded"
     */
    private array $ledgerEntry = [];

    /**
     * @var LockingInfoType|null
     *
     * minOccurs="0"
     * Information about how and when this record achieved status as "entered"
     * according to BFN AR 2013:2. Applies to all subitems if not otherwise specified.
     */
    private ?LockingInfoType $lockingInfo = null;

    /**
     * @var VoucherReferenceType[]
     *
     * Attribute minOccurs="0" maxOccurs="unbounded"
     * Reference to voucher or other document related to the journal entry
     */
    private array $voucherReference = [];

    /**
     * @var CorrectedByType[]
     *
     * Attribute minOccurs="0" maxOccurs="unbounded"
     * If this entry is corrected by other entries,
     * this is a reference to one correcting journal entry
     */
    private array $correctedBy = [];

    /**
     * @var int|null
     *
     * Attribute name="id" type="xsd:nonNegativeInteger" use="required"
     * Journal entry identifier
     */
    private ?int $id = null;

    /**
     * @var DateTime|null
     *
     * Attribute name="journalDate" type="xsd:date" use="required"
     * Journal date.
     * The date assigned to the entire journal entry at entry time.
     * Normally used as the date for posting to all subitems (ledger entries) to the general ledger.
     */
    private ?DateTime $journalDate;

    /**
     * @var string|null
     *
     * Attribute name="text" type="xsd:string"
     * Optional text describing the journal entry.
     */
    private ?string $text = null;

    /**
     * @var string|null
     *
     * Attribute name="referenceId" type="xsd:string"
     * Optional reference to identifier assigned befor the entry reached the accounting system.
     */
    private ?string $referenceId = null;

    /**
     * Factory method, set id, journalDate, text and EntryInfoType::date/by (all but text required)
     *
     * Same date for JournalEntryType::journalDate and EntryInfoType::date (today if null)
     *
     * @param string        $by
     * @param null|DateTime $journalDate
     * @param null|string   $id
     * @param null|string   $text
     * @return static
     * @throws InvalidArgumentException
     */
    public static function factoryByDateIdText(
        string $by,
        ? DateTime $journalDate = null,
        ? string $id = null,
        ? string $text = null
    ) : self
    {
        $instance = new self();
        $instance->setId( $id );
        if( empty( $journalDate )) {
            $journalDate = new DateTime();
        }
        $instance->setJournalDate( $journalDate );
        if( ! empty( $by )) {
            $instance->setEntryInfo(
                EntryInfoType::factoryByDate( $by, $journalDate )
            );
        }
        if( ! empty( $text )) {
            $instance->setText( $text );
        }
        return $instance;
    }

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->journalDate = new DateTime();
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = $inside = [];
        if( empty( $this->entryInfo )) {
            $local[] = self::errMissing(self::class, self::ENTRYINFO );
        }
        elseif( ! $this->entryInfo->isValid( $inside )) {
            $local[] = $inside;
            $inside  = [];
        }
        if( ! empty( $this->originalEntryInfo ) &&
            ! $this->originalEntryInfo->isValid( $inside )) {
            $local[] = $inside;
            $inside  = [];
        }
        if( ! empty( $this->ledgerEntry )) {
            foreach( array_keys( $this->ledgerEntry ) as $ix ) { // element ix
                $inside[$ix] = [];
                if( $this->ledgerEntry[$ix]->isValid( $inside[$ix] )) {
                    unset($inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::LEDGERENTRY );
                $local[$key] = $inside;
                $inside      = [];
            } // end if
        } // end if
        if( ! empty( $this->lockingInfo ) &&
            ! $this->lockingInfo->isValid( $inside )) {
            $local[] = $inside;
            $inside  = [];
        } // end if
        if( ! empty( $this->voucherReference )) {
            foreach( array_keys( $this->voucherReference ) as $ix ) { // element ix
                $inside[$ix] = [];
                if( $this->voucherReference[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::VOUCHERREFERENCE );
                $local[$key] = $inside;
                $inside      = [];
            } // end if
        } // end if
        if( ! empty( $this->correctedBy )) {
            foreach( array_keys( $this->correctedBy ) as $ix ) { // element ix
                $inside[$ix] = [];
                if( $this->correctedBy[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::CORRECTEDBY );
                $local[$key] = $inside;
                $inside      = [];
            } // end if
        } // end if
        if( null === $this->id ) {
            $local[] = self::errMissing(self::class, self::ID );
        }
        if( null === $this->journalDate ) {
            $local[] = self::errMissing(self::class, self::JOURNALDATE );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return null|EntryInfoType
     */
    public function getEntryInfo() : ?EntryInfoType
    {
        return $this->entryInfo;
    }

    /**
     * @param EntryInfoType $entryInfo
     * @return static
     */
    public function setEntryInfo( EntryInfoType $entryInfo ) : self
    {
        $this->entryInfo = $entryInfo;
        return $this;
    }

    /**
     * @return null|OriginalEntryInfoType
     */
    public function getOriginalEntryInfo() : ?OriginalEntryInfoType
    {
        return $this->originalEntryInfo;
    }

    /**
     * @param OriginalEntryInfoType $originalEntryInfo
     * @return static
     */
    public function setOriginalEntryInfo( OriginalEntryInfoType $originalEntryInfo ) : self
    {
        $this->originalEntryInfo = $originalEntryInfo;
        return $this;
    }

    /**
     * Add single LedgerEntryType
     *
     * @param LedgerEntryType $ledgerEntry
     * @return static
     */
    public function addLedgerEntry( LedgerEntryType $ledgerEntry ) : self
    {
        $this->ledgerEntry[] = $ledgerEntry;
        return $this;
    }

    /**
     * @return array
     */
    public function getLedgerEntry() : array
    {
        return $this->ledgerEntry;
    }

    /**
     * Return array ledgerEntry AccountsIds
     *
     * @return array
     */
    public function getAllLedgerEntryAccountIds() : array
    {
        $accountIds = [];
        foreach( array_keys( $this->ledgerEntry ) as $ix ) {
            $accountIds[] = $this->ledgerEntry[$ix]->getAccountId();
        } // end foreach
        sort( $accountIds );
        return array_unique( $accountIds );
    }

    /**
     * Return bool true if sum of ledgerEntry amounts is zero
     *
     * @return bool
     */
    public function hasBalancedLedgerEntries() : bool
    {
        $amount = 0.00;
        foreach( array_keys( $this->ledgerEntry ) as $ix1 ) {
            $amount += $this->ledgerEntry[$ix1]->getAmount();
        } // end foreach
        return ( 0.00 === CommonFactory::assertAmount( $amount ));
    }

    /**
     * Set LedgerEntryTypes, array
     *
     * @param LedgerEntryType[] $ledgerEntry
     * @return static
     * @throws TypeError
     */
    public function setLedgerEntry( array $ledgerEntry ) : self
    {
        foreach( $ledgerEntry as $value ) {
            $this->addLedgerEntry( $value );
        } // end foreach
        return $this;
    }

    /**
     * @return LockingInfoType|null
     */
    public function getLockingInfo() : ?LockingInfoType
    {
        return $this->lockingInfo;
    }

    /**
     * @param LockingInfoType $lockingInfo
     * @return static
     */
    public function setLockingInfo( LockingInfoType $lockingInfo ) : self
    {
        $this->lockingInfo = $lockingInfo;
        return $this;
    }

    /**
     * Add single VoucherReferenceType
     *
     * @param VoucherReferenceType $voucherReference
     * @return static
     */
    public function addVoucherReference( VoucherReferenceType $voucherReference ) : self
    {
        $this->voucherReference[] = $voucherReference;
        return $this;
    }

    /**
     * @return VoucherReferenceType[]
     */
    public function getVoucherReference() : array
    {
        return $this->voucherReference;
    }

    /**
     * Return array with all VoucherReference dokumentIds
     *
     * @return array
     */
    public function getAllVoucherReferenceDocumentIds() : array
    {
        $documentIds = [];
        foreach( array_keys( $this->voucherReference ) as $ix ) { // element ix
            $documentIds[$ix] = $this->voucherReference[$ix]->getDocumentId();
        } // end foreach
        return $documentIds;
    }

    /**
     * Setn VoucherReferenceTypes, array
     *
     * @param VoucherReferenceType[] $voucherReference
     * @return static
     * @throws TypeError
     */
    public function setVoucherReference( array $voucherReference ) : self
    {
        foreach( $voucherReference as $value ) {
            $this->addVoucherReference( $value );
        } // end foreach
        return $this;
    }

    /**
     * Add single CorrectedByType
     *
     * @param CorrectedByType $correctedBy
     * @return static
     */
    public function addCorrectedBy( CorrectedByType $correctedBy ) : self
    {
        $this->correctedBy[] = $correctedBy;
        return $this;
    }
    /**
     * @return CorrectedByType[]
     */
    public function getCorrectedBy() : array
    {
        return $this->correctedBy;
    }

    /**
     * Set CorrectedByTypes, array
     *
     * @param CorrectedByType[] $correctedBy
     * @return static
     * @throws TypeError
     */
    public function setCorrectedBy( array $correctedBy ) : self
    {
        foreach( $correctedBy as $value ) {
            $this->addCorrectedBy( $value );
        } // end foreach
        return $this;
    }

    /**
     * @return null|int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return JournalEntryType
     */
    public function setId( mixed $id ) : self
    {
        $this->id = CommonFactory::assertNonNegativeInteger( $id );
        return $this;
    }

    /**
     * @return null|DateTime
     */
    public function getJournalDate() : ?DateTime
    {
        return $this->journalDate;
    }

    /**
     * @param DateTime $journalDate
     * @return JournalEntryType
     * @throws InvalidArgumentException
     */
    public function setJournalDate( DateTime $journalDate ) : self
    {
        $this->journalDate = $journalDate;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getText() : ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return JournalEntryType
     */
    public function setText( string $text ) : self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReferenceId() : ?string
    {
        return $this->referenceId;
    }

    /**
     * @param string $referenceId
     * @return JournalEntryType
     */
    public function setReferenceId( string $referenceId ) : self
    {
        $this->referenceId = $referenceId;
        return $this;
    }
}
