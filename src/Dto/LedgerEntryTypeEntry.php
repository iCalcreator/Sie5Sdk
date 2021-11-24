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
use function current;
use function get_class;
use function is_array;
use function key;
use function reset;
use function sprintf;

class LedgerEntryTypeEntry extends Sie5DtoExtAttrBase
{
    /**
     * @var array         SETS of   0-1        ForeignCurrencyAmountType
     *                              0-unbound  ObjectReferenceType
     *                              0-1        SubdividedAccountObjectReferenceType
     */
    private array $ledgerEntryTypeEntries = [];

    /**
     * @var string|null
     */
    private ?string $previousElement        = null;

    /**
     * @var int
     */
    private int $elementSetIndex        = 0;

    /**
     * @var string|null
     *
     * Attribute name="accountId" type="xsd:string" use="required"
     * Account identifier. Must exist in the chart of accounts
     */
    private ?string $accountId = null;

    /**
     * @var float|null
     *
     * Attribute name="amount" type="xsd:decimal" use="required"
     * Amount. Positive for debit, negative for credit. May not be zero???
     */
    private ?float $amount = null;

    /**
     * @var float|null
     *
     * Attribute name="quantity" type="xsd:decimal"
     */
    private ?float $quantity = null;

    /**
     * @var string|null
     *
     * Attribute name="text" type="xsd:string"
     * Optional text describing the individual ledger entry.
     */
    private ?string $text = null;

    /**
     * @var DateTime|null
     *
     * Attribute name="ledgerDate" type="xsd:date" use="optional"
     * The date used for posting to the general ledger
     * if different from the journal date specified for the entire journal entry.
     */
    private ?DateTime $ledgerDate = null;

    /**
     * Factory method, set account, amount and, opt, quantity
     *
     * @param string $accountId
     * @param mixed $amount
     * @param mixed|null $quantity
     * @return static
     */
    public static function factoryAccountAmount( string $accountId, mixed $amount, mixed $quantity = null ) : self
    {
        $instance = new self();
        $instance->setAccountId( $accountId );
        $instance->setAmount( $amount );
        if( ! empty( $quantity ) || ( 0 === (int) $quantity )) {
            $instance->setQuantity( $quantity );
        }
        return $instance;
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
        if( ! empty( $this->ledgerEntryTypeEntries )) {
            $inside = [];
            foreach( array_keys( $this->ledgerEntryTypeEntries ) as $x1 ) { // $elementSet x1
                $inside[$x1] = [];
                foreach( array_keys( $this->ledgerEntryTypeEntries[$x1] ) as $x2 ) { // keyed element x2
                    $inside[$x1][$x2] = [];
                    reset( $this->ledgerEntryTypeEntries[$x1][$x2] );
                    $key    = key( $this->ledgerEntryTypeEntries[$x1][$x2] );
                    if( $this->ledgerEntryTypeEntries[$x1][$x2][$key]->isValid( $inside[$x1][$x2] )) {
                        unset( $inside[$x1][$x2] );
                    }
                } // end foreach
                if( empty( $inside[$x1] )) {
                    unset( $inside[$x1] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::LEDGERENTRY );
                $local[$key] = $inside;
            } // end if
        } // end if
        if( null === $this->accountId ) {
            $local[] = self::errMissing(self::class, self::ACCOUNTID );
        }
        if( null === $this->amount ) {
            $local[] = self::errMissing(self::class, self::AMOUNT );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single (typed) LedgerEntryType
     *
     * Type : FOREIGNCURRENCYAMOUNT / OBJECTREFERENCE / SUBDIVIDEDACCOUNTOBJECTREFERENCE
     *
     * @param string $key
     * @param LedgerEntryTypeEntriesInterface $ledgerEntryType
     * @return static
     */
    public function addLedgerEntryTypeEntry( string $key, LedgerEntryTypeEntriesInterface $ledgerEntryType ) : self
    {
        switch( true ) {
            case (( self::FOREIGNCURRENCYAMOUNT === $key ) &&
                $ledgerEntryType instanceof ForeignCurrencyAmountType ) :
                if( ! empty( $this->previousElement )) {
                    ++$this->elementSetIndex;
                }
                break;
            case (( self::OBJECTREFERENCE === $key ) &&
                $ledgerEntryType instanceof ObjectReferenceType ) :
                if( self::SUBDIVIDEDACCOUNTOBJECTREFERENCE === $this->previousElement ) {
                    ++$this->elementSetIndex;
                }
                break;
            case (( self::SUBDIVIDEDACCOUNTOBJECTREFERENCE === $key ) &&
                $ledgerEntryType instanceof SubdividedAccountObjectReferenceType ) :
                if( self::SUBDIVIDEDACCOUNTOBJECTREFERENCE === $this->previousElement ) {
                    ++$this->elementSetIndex;
                }
                break;
            default :
                throw new InvalidArgumentException(
                    sprintf( self::$FMTERR5, self::LEDGERENTRY, $key, get_class( $ledgerEntryType ))
                );
        } // end switch
        $this->ledgerEntryTypeEntries[$this->elementSetIndex][] = [ $key => $ledgerEntryType ];
        $this->previousElement = $key;
        return $this;
    }

    /**
     * @return array
     */
    public function getLedgerEntryTypeEntries(): array
    {
        return $this->ledgerEntryTypeEntries;
    }

    /**
     * Set LedgerEntryTypes, array, LedgerEntryTypesInterface[] OR *[ type => LedgerEntryTypesInterface ]
     *
     * Type : FOREIGNCURRENCYAMOUNT / OBJECTREFERENCE / SUBDIVIDEDACCOUNTOBJECTREFERENCE
     *
     * @param array $ledgerEntryTypes
     * @return static
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function setLedgerEntryTypeEntries( array $ledgerEntryTypes ) : self
    {
        foreach( $ledgerEntryTypes as $ix1 => $elementSet ) {
            if( ! is_array( $elementSet )) {
                $elementSet = [ $ix1 => $elementSet ];
            }
            foreach( $elementSet as $ix2 => $element ) {
                switch( true ) {
                    case is_array( $element ) :
                        break;
                    case ( $element instanceof ForeignCurrencyAmountType ) :
                        $element = [ self::FOREIGNCURRENCYAMOUNT => $element ];
                        break;
                    case ( $element instanceof ObjectReferenceType ) :
                        $element = [ self::OBJECTREFERENCE => $element ];
                        break;
                    case ( $element instanceof SubdividedAccountObjectReferenceType ) :
                        $element = [ self::SUBDIVIDEDACCOUNTOBJECTREFERENCE => $element ];
                        break;
                    default :
                        $element = [ $ix2 => $element ];
                } // end switch
                reset( $element );
                $key = (string) key( $element );
                $this->addLedgerEntryTypeEntry( $key,  current( $element ));
            }  // end foreach
        } // end foreach
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAccountId() : ?string
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId
     * @return static
     */
    public function setAccountId( string $accountId ) : self
    {
        $this->accountId = CommonFactory::assertAccountNumber( $accountId );
        return $this;
    }

    /**
     * @return null|float
     */
    public function getAmount() : ?float
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAmount( mixed $amount ) : self
    {
        $this->amount = CommonFactory::assertAmount( $amount );
        return $this;
    }

    /**
     * @return null|float
     */
    public function getQuantity() : ?float
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return static
     * @throws InvalidArgumentException
     */
    public function setQuantity( mixed $quantity ) : self
    {
        $this->quantity = (float) $quantity;
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
     * @return static
     */
    public function setText( string $text ) : self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return null|DateTime
     */
    public function getLedgerDate() : ?DateTime
    {
        return $this->ledgerDate;
    }

    /**
     * @param DateTime $ledgerDate
     * @return static
     */
    public function setLedgerDate( DateTime $ledgerDate ) : self
    {
        $this->ledgerDate = $ledgerDate;
        return $this;
    }
}
