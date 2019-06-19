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

use function array_keys;
use function array_unique;
use function gettype;
use function sort;
use function sprintf;

class JournalEntryType extends Sie5DtoExtAttrBase
{

    /**
     * @var EntryInfoType
     *                   Information about how and when this record was entered (registered) into the system.
     *                   Applies to all subitems if not otherwise specified.
     * @access private
     */
    private $entryInfo = null;

    /**
     * @var OriginalEntryInfoType
     *                   minOccurs="0"
     *                   Information about how and when this record originally was entered (registered)
     *                   into some pre-system.
     * @access private
     */
    private $originalEntryInfo = null;

    /**
     * @var array    [ *LedgerEntryType ]
     *                        minOccurs="0" maxOccurs="unbounded"
     *                       Container for ledger entries
     * @access private
     */
    private $ledgerEntry = [];

    /**
     * @var LockingInfoType
     *                      minOccurs="0"
     *                     Information abot how and when this record achieved status as "entered"
     *                     according to BFN AR 2013:2. Applies to all subitems if not otherwise specified.
     * @access private
     */
    private $lockingInfo = null;

    /**
     * @var array  [ *VoucherReferenceType ]
     *                            minOccurs="0" maxOccurs="unbounded"
     *                            Reference to voucher or other document related to the journal entry
     * @access private
     */
    private $voucherReference = [];

    /**
     * @var array     [ *CorrectedByType ]
     *                       minOccurs="0" maxOccurs="unbounded"
     *                       If this entry is corrected by other entries,
     *                       this is a reference to one correcting journal entry
     * @access private
     */
    private $correctedBy = [];

    /**
     * @var int
     *         attribute name="id" type="xsd:nonNegativeInteger" use="required"
     *         Journal entry identifier
     * @access private
     */
    private $id = null;

    /**
     * @var DateTime
     *            attribute name="journalDate" type="xsd:date" use="required"
     *            Journal date.
     *            The date assigned to the entire journal entry at entry time.
     *            Normally used as the date for posting to all subitems (ledger entries) to the general ledger.
     * @access private
     */
    private $journalDate = null;

    /**
     * @var string
     *            attribute name="text" type="xsd:string"
     *            Optional text describing the journal entry.
     * @acces private
     */
    private $text = null;

    /**
     * @var string
     *            attribute name="referenceId" type="xsd:string"
     *            Optional reference to identifier assigned befor the entry reached the accounting system.
     * @access private
     */
    private $referenceId = null;

    /**
     * Class constructor
     *
     */
    public function __construct() {
        parent::__construct();
        $this->journalDate = new DateTime();
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = $inside = [];
        if( empty( $this->entryInfo )) {
            $local[self::ENTRYINFO] = false;
        }
        elseif( ! $this->entryInfo->isValid( $inside )) {
            $local[self::ENTRYINFO] = $inside;
            $inside = [];
        }
        if( ! empty( $this->originalEntryInfo ) && ! $this->originalEntryInfo->isValid( $inside )) {
            $local[self::ORIGINALENTRYINFO] = $inside;
        }
        if( ! empty( $this->ledgerEntry )) {
            foreach( array_keys( $this->ledgerEntry ) as $ix ) { // element ix
                if( ! $this->ledgerEntry[$ix]->isValid( $inside ) ) {
                    $local[self::LEDGERENTRY][$ix] = $inside;
                }
                $inside = [];
            }

        }
        if( ! empty( $this->lockingInfo ) && ! $this->lockingInfo->isValid( $inside )) {
            $local[self::LOCKINGINFO] = $inside;
            $inside = [];
        }
        if( ! empty( $this->voucherReference )) {
            foreach( array_keys( $this->voucherReference ) as $ix ) { // element ix
                if( ! $this->voucherReference[$ix]->isValid( $inside ) ) {
                    $local[self::VOUCHERREFERENCE][$ix] = $inside;
                }
                $inside = [];
            }
        }
        if( ! empty( $this->correctedBy )) {
            foreach( array_keys( $this->correctedBy ) as $ix ) { // element ix
                if( ! $this->correctedBy[$ix]->isValid( $inside ) ) {
                    $local[self::CORRECTEDBY][$ix] = $inside;
                }
                $inside = [];
            }
        }
        if( is_null( $this->id )) {
            $local[self::ID] = false;
        }
        if( empty( $this->journalDate )) {
            $local[self::JOURNALDATE] = false;
        }
        if( ! empty( $local )) {
            $expected[self::JOURNALENTRY] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return EntryInfoType
     */
    public function getEntryInfo() {
        return $this->entryInfo;
    }

    /**
     * @param EntryInfoType $entryInfo
     * @return static
     */
    public function setEntryInfo( EntryInfoType $entryInfo ) {
        $this->entryInfo = $entryInfo;
        return $this;
    }

    /**
     * @return OriginalEntryInfoType
     */
    public function getOriginalEntryInfo() {
        return $this->originalEntryInfo;
    }

    /**
     * @param OriginalEntryInfoType $originalEntryInfo
     * @return static
     */
    public function setOriginalEntryInfo( OriginalEntryInfoType $originalEntryInfo ) {
        $this->originalEntryInfo = $originalEntryInfo;
        return $this;
    }

    /**
     * @param LedgerEntryType $ledgerEntry
     * @return static
     */
    public function addLedgerEntry( LedgerEntryType $ledgerEntry ) {
        $this->ledgerEntry[] = $ledgerEntry;
        return $this;
    }

    /**
     * @return array
     */
    public function getLedgerEntry() {
        return $this->ledgerEntry;
    }

    /**
     * Return array ledgerEntry AccountsIds
     *
     * @return array
     */
    public function getAllLedgerEntryAccountIds() {
        $accountIds = [];
        foreach( array_keys( $this->ledgerEntry ) as $ix ) {
            $accountIds[] = $this->ledgerEntry[$ix]->getAccountId();
        }
        sort( $accountIds );
        return array_unique( $accountIds );
    }

    /**
     * Return bool true if sum of ledgerEntry amounts is zero
     *
     * @return bool
     */
    public function hasBalancedLedgerEntries() {
        $amount = 0.00;
        foreach( array_keys( $this->ledgerEntry ) as $ix1 ) {
            $amount += $this->ledgerEntry[$ix1]->getAmount();
        }
        return ( 0.00 == CommonFactory::assertAmount( $amount ));
    }

    /**
     * @param array $ledgerEntry
     * @return static
     * @throws InvalidArgumentException
     */
    public function setLedgerEntry( array $ledgerEntry ) {
        foreach( $ledgerEntry as $ix => $value ) {
            if( $value instanceof LedgerEntryType ) {
                $this->ledgerEntry[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::LEDGERENTRY, $ix, $type ));
            }
        }
        return $this;
    }

    /**
     * @return LockingInfoType
     */
    public function getLockingInfo() {
        return $this->lockingInfo;
    }

    /**
     * @param LockingInfoType $lockingInfo
     * @return static
     */
    public function setLockingInfo( LockingInfoType $lockingInfo ) {
        $this->lockingInfo = $lockingInfo;
        return $this;
    }

    /**
     * @param VoucherReferenceType $voucherReference
     * @return static
     */
    public function addVoucherReference( VoucherReferenceType $voucherReference ) {
        $this->voucherReference[] = $voucherReference;
        return $this;
    }

    /**
     * @return array
     */
    public function getVoucherReference() {
        return $this->voucherReference;
    }

    /**
     * Return array with all VoucherReference dokumentIds
     *
     * @return array
     */
    public function getAllVoucherReferenceDocumentIds() {
        $documentIds = [];
        foreach( array_keys( $this->voucherReference ) as $ix ) { // element ix
            $documentIds[$ix] = $this->voucherReference[$ix]->getDocumentId();
        }
        return $documentIds;
    }

    /**
     * @param array $voucherReference VoucherReferenceType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setVoucherReference( array $voucherReference ) {
        foreach( $voucherReference as $ix => $value ) {
            if( $value instanceof VoucherReferenceType ) {
                $this->voucherReference[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::VOUCHERREFERENCE, $ix, $type ));
            }
        }
        return $this;
    }

    /**
     * @param CorrectedByType $correctedBy
     * @return static
     */
    public function addCorrectedBy( CorrectedByType $correctedBy ) {
        $this->correctedBy[] = $correctedBy;
        return $this;
    }
    /**
     * @return array
     */
    public function getCorrectedBy() {
        return $this->correctedBy;
    }

    /**
     * @param array $correctedBy
     * @return static
     * @throws InvalidArgumentException
     */
    public function setCorrectedBy( array $correctedBy ) {
        foreach( $correctedBy as $ix => $value ) {
            if( $value instanceof CorrectedByType ) {
                $this->correctedBy[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::CORRECTEDBY, $ix, $type ));
            }
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return JournalEntryType
     * @throws InvalidArgumentException
     */
    public function setId( $id ) {
        $this->id = CommonFactory::assertInt( $id );
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getJournalDate() {
        return $this->journalDate;
    }

    /**
     * @param DateTime $journalDate
     * @return JournalEntryType
     * @throws InvalidArgumentException
     */
    public function setJournalDate( DateTime $journalDate ) {
        $this->journalDate = $journalDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param string $text
     * @return JournalEntryType
     * @throws InvalidArgumentException
     */
    public function setText( $text ) {
        $this->text = CommonFactory::assertString( $text );
        return $this;
    }

    /**
     * @return string
     */
    public function getReferenceId() {
        return $this->referenceId;
    }

    /**
     * @param string $referenceId
     * @return JournalEntryType
     * @throws InvalidArgumentException
     */
    public function setReferenceId( $referenceId ) {
        $this->referenceId = CommonFactory::assertString( $referenceId );
        return $this;
    }

}