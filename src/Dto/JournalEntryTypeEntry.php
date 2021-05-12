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

use DateTime;
use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

use function array_keys;
use function array_unique;
use function gettype;
use function sort;
use function sprintf;

class JournalEntryTypeEntry extends Sie5DtoExtAttrBase
{
    /**
     * @var OriginalEntryInfoType
     *                           minOccurs="1"
     *                           Information about how and when this record originally
     *                           was entered (registered) into some pre-system.
     */
    private $originalEntryInfo = null;

    /**
     * @var LedgerEntryTypeEntry[]
     *
     * Attribute minOccurs="0" maxOccurs="unbounded"
     * Container for ledger entries
     */
    private $ledgerEntry = [];

    /**
     * @var VoucherReferenceType[]
     *
     * Attribute minOccurs="0" maxOccurs="unbounded"
     * Reference to voucher or other source document(s)
     */
    private $voucherReference = [];

    /**
     * @var int
     *
     * Attribute name="id" type="xsd:nonNegativeInteger" use="optional"
     * Journal identifier
     */
    private $id = null;

    /**
     * @var DateTime
     *
     * Attribute name="journalDate" type="xsd:date" use="required"
     * Journal date.
     * The date assigned to the entire journal entry at entry time.
     * Normally used as the date for posting to all subitems (ledger entries) to the general ledger.
     */
    private $journalDate = null;

    /**
     * @var string
     *
     * Attribute name="text" type="xsd:string"
     * Optional text describing the journal entry.
     */
    private $text = null;

    /**
     * @var string
     *
     * Attribute name="referenceId" type="xsd:string"
     * Optional reference to identifier assigned befor the entry reached the accounting system.
     */
    private $referenceId = null;

    /**
     * Factory method, set id (opt), journalDate (required), text (opt) and OriginalEntryInfoType::date/by (required)
     *
     * Same date for JournalEntryTypeEntry::journalDate and OriginalEntryInfoType::date (today if null)
     *
     * @param string        $by
     * @param DateTime|null $journalDate
     * @param mixed|null   $id
     * @param string|null   $text
     * @return static
     * @throws InvalidArgumentException
     */
    public static function factoryByDateIdText(
        string $by = null,
        DateTime $journalDate = null,
        $id = null,
        string $text = null
    ) : self
    {
        $instance = new self();
        if( ! empty( $id )) {
            $instance->setId( $id );
        }
        if( empty( $journalDate )) {
            $journalDate = new DateTime();
        }
        $instance->setJournalDate( $journalDate )
            ->setOriginalEntryInfo( OriginalEntryInfoType::factoryByDate( $by, $journalDate ));
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
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) : bool
    {
        $local = $inside = [];
        if( empty( $this->originalEntryInfo )) {
            $local[self::ORIGINALENTRYINFO] = false;
        }
        elseif( ! $this->originalEntryInfo->isValid( $inside )) {
            $local[self::ORIGINALENTRYINFO] = $inside;
            $inside = [];
        }
        if( ! empty( $this->ledgerEntry )) {
            foreach( array_keys( $this->ledgerEntry ) as $ix1 ) { // element ix
                if( ! $this->ledgerEntry[$ix1]->isValid( $inside ) ) {
                    $local[self::LEDGERENTRY][$ix1] = $inside;
                }
                $inside = [];
            } // end foreach
        }
        if( ! empty( $this->voucherReference )) {
            foreach( array_keys( $this->voucherReference ) as $ix1 ) { // element ix
                if( ! $this->voucherReference[$ix1]->isValid( $inside ) ) {
                    $local[self::VOUCHERREFERENCE][$ix1] = $inside;
                }
                $inside = [];
            } // end foreach
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
     * @return OriginalEntryInfoType
     */
    public function getOriginalEntryInfo() : OriginalEntryInfoType
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
     * @param LedgerEntryTypeEntry $ledgerEntry
     * @return static
     */
    public function addLedgerEntry( LedgerEntryTypeEntry $ledgerEntry ) : self
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
        foreach( array_keys( $this->ledgerEntry ) as $ix1 ) {
            $accountIds[] = $this->ledgerEntry[$ix1]->getAccountId();
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
        return ( 0.00 == CommonFactory::assertAmount( $amount ));
    }

    /**
     * @param LedgerEntryTypeEntry[] $ledgerEntry
     * @return static
     * @throws InvalidArgumentException
     */
    public function setLedgerEntry( array $ledgerEntry ) : self
    {
        foreach( $ledgerEntry as $ix => $value ) {
            if( $value instanceof LedgerEntryTypeEntry ) {
                $this->ledgerEntry[] = $value;
                continue;
            }
            $type = gettype( $value );
            if( self::$OBJECT == $type ) {
                $type = get_class( $value );
            }
            throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::LEDGERENTRY, $ix, $type ));
        } // end foreach
        return $this;
    }

    /**
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
    public function getVoucherReference()
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
     * @param VoucherReferenceType[] $voucherReference
     * @return static
     * @throws InvalidArgumentException
     */
    public function setVoucherReference( array $voucherReference ) : self
    {
        foreach( $voucherReference as $ix => $value ) {
            if( $value instanceof VoucherReferenceType ) {
                $this->voucherReference[] = $value;
                continue;
            }
            $type = gettype( $value );
            if( self::$OBJECT == $type ) {
                $type = get_class( $value );
            }
            throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::VOUCHERREFERENCE, $ix, $type ));
        } // end foreach
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return static
     * @throws InvalidArgumentException
     */
    public function setId( $id ) : self
    {
        $this->id = CommonFactory::assertNonNegativeInteger( $id );
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getJournalDate() : DateTime
    {
        return $this->journalDate;
    }

    /**
     * @param DateTime $journalDate
     * @return static
     */
    public function setJournalDate( DateTime $journalDate ) : self
    {
        $this->journalDate = $journalDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return static
     */
    public function setText( string $text ) : self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * @param string $referenceId
     * @return static
     */
    public function setReferenceId( string $referenceId ) : self
    {
        $this->referenceId = $referenceId;
        return $this;
    }
}
