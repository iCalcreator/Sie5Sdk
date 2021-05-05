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
    private $ledgerEntryTypeEntries = [];

    /**
     * @var string
     */
    private $previousElement        = null;

    /**
     * @var int
     */
    private $elementSetIndex        = 0;

    /**
     * @var string
     *
     * Attribute name="accountId" type="xsd:string" use="required"
     * Account identifier. Must exist in the chart of accounts
     */
    private $accountId = null;

    /**
     * @var float
     *
     * Attribute name="amount" type="xsd:decimal" use="required"
     * Amount. Positive for debit, negative for credit. May not be zero???
     */
    private $amount = null;

    /**
     * @var float
     *
     * Attribute name="quantity" type="xsd:decimal"
     */
    private $quantity = null;

    /**
     * @var string
     *
     * Attribute name="text" type="xsd:string"
     * Optional text describing the individual ledger entry.
     */
    private $text = null;

    /**
     * @var DateTime
     *
     * Attribute name="ledgerDate" type="xsd:date" use="optional"
     * The date used for posting to the general ledger
     * if different from the journal date specified for the entire journal entry.
     */
    private $ledgerDate = null;

    /**
     * Factory method, set account, amount and, opt, quantity
     *
     * @param string $accountId
     * @param mixed  $amount
     * @param mixed  $quantity
     * @return static
     * @throws InvalidArgumentException
     */
    public static function factoryAccountAmount( string $accountId, $amount, $quantity = null ) : self
    {
        $instance = new self();
        $instance->setAccountId( $accountId );
        $instance->setAmount( $amount );
        if( null !== $quantity ) {
            $instance->setQuantity( $quantity );
        }
        return $instance;
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) : bool
    {
        $local = [];
        if( ! empty( $this->ledgerEntryTypeEntries )) {
            foreach( array_keys( $this->ledgerEntryTypeEntries ) as $ix1 ) { // $elementSet ix1
                foreach( array_keys( $this->ledgerEntryTypeEntries[$ix1] ) as $ix2 ) { // $element ix2
                    $inside = [];
                    reset( $this->ledgerEntryTypeEntries[$ix1][$ix2] );
                    $key    = key( $this->ledgerEntryTypeEntries[$ix1][$ix2] );
                    if( ! $this->ledgerEntryTypeEntries[$ix1][$ix2][$key]->isValid( $inside )) {
                        $local[self::LEDGERENTRY][$ix1][$ix2][$key] = $inside;
                    }
                } // end foreach
            } // end foreach
        }
        if( empty( $this->accountId )) {
            $local[self::ACCOUNTID] = false;
        }
        if( null == $this->amount ) {
            $local[self::AMOUNT] = false;
        }
        if( ! empty( $local )) {
            $expected[self::LEDGERENTRY] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param string $key
     * @param LedgerEntryTypeEntriesInterface $ledgerEntryType
     * @return static
     * @throws InvalidArgumentException
     */
    public function addLedgerEntryTypeEntry( string $key, LedgerEntryTypeEntriesInterface $ledgerEntryType ) : self
    {
        switch( true ) {
            case (( self::FOREIGNCURRENCYAMOUNT == $key ) &&
                $ledgerEntryType instanceof ForeignCurrencyAmountType ) :
                if( ! empty( $this->previousElement )) {
                    $this->elementSetIndex += 1;
                }
                break;
            case (( self::OBJECTREFERENCE == $key ) &&
                $ledgerEntryType instanceof ObjectReferenceType ) :
                if( self::SUBDIVIDEDACCOUNTOBJECTREFERENCE == $this->previousElement ) {
                    $this->elementSetIndex += 1;
                }
                break;
            case (( self::SUBDIVIDEDACCOUNTOBJECTREFERENCE == $key ) &&
                $ledgerEntryType instanceof SubdividedAccountObjectReferenceType ) :
                if( self::SUBDIVIDEDACCOUNTOBJECTREFERENCE == $this->previousElement ) {
                    $this->elementSetIndex += 1;
                }
                break;
            default :
                throw new InvalidArgumentException(
                    sprintf( self::$FMTERR5, self::LEDGERENTRY, $key, get_class( $ledgerEntryType ))
                );
                break;
        } // end switch
        $this->ledgerEntryTypeEntries[$this->elementSetIndex][] = [ $key => $ledgerEntryType ];
        $this->previousElement = $key;
        return $this;
    }

    /**
     * @return array
     */
    public function getLedgerEntryTypeEntries()
    {
        return $this->ledgerEntryTypeEntries;
    }

    /**
     * Set LedgerEntryTypes, array, *LedgerEntryTypesInterface OR *[ type => LedgerEntryTypesInterface ]
     *
     * Type : FOREIGNCURRENCYAMOUNT / OBJECTREFERENCE / SUBDIVIDEDACCOUNTOBJECTREFERENCE
     *
     * @param array $ledgerEntryTypes
     * @return static
     * @throws InvalidArgumentException
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
                $key             = key( $element );
                $ledgerEntryType = current( $element );
                switch( true ) {
                    case (( self::FOREIGNCURRENCYAMOUNT == $key ) &&
                        $ledgerEntryType instanceof ForeignCurrencyAmountType ) :
                        break;
                    case (( self::OBJECTREFERENCE == $key ) && $ledgerEntryType instanceof ObjectReferenceType ) :
                        break;
                    case (( self::SUBDIVIDEDACCOUNTOBJECTREFERENCE == $key ) &&
                        $ledgerEntryType instanceof SubdividedAccountObjectReferenceType ) :
                        break;
                    default :
                        throw new InvalidArgumentException(
                            sprintf( self::$FMTERR52, self::LEDGERENTRY, $ix1, $ix2, $key, get_class( $ledgerEntryType ))
                        );
                        break;
                } // end switch
                $this->ledgerEntryTypeEntries[$ix1][$ix2] = $element;
            }  // end foreach
        } // end foreach
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountId() : string
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAccountId( string $accountId ) : self
    {
        $this->accountId = CommonFactory::assertAccountNumber( $accountId );
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount() : float
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAmount( $amount ) : self
    {
        $this->amount = CommonFactory::assertAmount( $amount );
        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return static
     * @throws InvalidArgumentException
     */
    public function setQuantity( $quantity ) : self
    {
        $this->quantity = (float) $quantity;
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
     * @return DateTime
     */
    public function getLedgerDate()
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
