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
use function current;
use function get_class;
use function is_array;
use function is_null;
use function key;
use function reset;
use function sprintf;

class LedgerEntryType extends Sie5DtoExtAttrBase
{

    /**
     * @var array
     *               elements are ( i.e. sets of [*(key => value)] )
     *                 ForeignCurrencyAmountType             minOccurs="0"
     *                 ObjectReferenceType                   minOccurs="0" maxOccurs="unbounded"
     *                 SubdividedAccountObjectReferenceType  minOccurs="0" maxOccurs="1"
     *                 EntryInfoType                         minOccurs="0"
     *                 OverstrikeType                        minOccurs="0"
     *                 LockingInfoType                       minOccurs="0"
     * @access private
     */
    private $ledgerEntryTypes  = [];
    private $previousElement   = null;
    private $elementSetIndex   = 0;
    private static $PREVIOUS23 = [
        self::SUBDIVIDEDACCOUNTOBJECTREFERENCE,
        self::ENTRYINFO,
        self::OVERSTRIKE,
        self::LOCKINGINFO
    ];
    private static $PREVIOUS4  = [ self::ENTRYINFO, self::OVERSTRIKE, self::LOCKINGINFO ];
    private static $PREVIOUS5  = [ self::OVERSTRIKE, self::LOCKINGINFO ];
    private static $PREVIOUS6  = [ self::LOCKINGINFO ];

    /**
     * @var int
     *            attribute name="accountId" type="sie:AccountNumber" use="required"
     *            Account identifier. Must exist in the chart of accounts
     * @access private
     */
    private $accountId = null;

    /**
     * @var string
     *            attribute name="amount" type="xsd:decimal" use="required"
     *            Amount. Positive for debit, negative for credit. May not be zero???
     * @access private
     */
    private $amount = null;

    /**
     * @var string
     *            attribute name="quantity" type="xsd:decimal"
     * @access private
     *
     */
    private $quantity = null;

    /**
     * @var string
     *            attribute name="text" type="xsd:string"
     *            Optional text describing the individual ledger entry.
     * @access private
     */
    private $text = null;

    /**
     * @var DateTime
     *            attribute name="ledgerDate" type="xsd:date" use="optional"
     *            The date used for posting to the general ledger if different from the
     *            journal date specified for the entire journal entry.
     * @access private
     */
    private $ledgerDate = null;



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( ! empty( $this->ledgerEntryTypes )) {
            foreach( array_keys( $this->ledgerEntryTypes ) as $ix1 ) { // elementSet ix1
                foreach( array_keys( $this->ledgerEntryTypes[$ix1] ) as $ix2 ) {   // element ix2
                    $inside = [];
                    reset( $this->ledgerEntryTypes[$ix1][$ix2] );
                    $key    = key( $this->ledgerEntryTypes[$ix1][$ix2] );
                    if( ! $this->ledgerEntryTypes[$ix1][$ix2][$key]->isValid( $inside )) {
                        $local[self::LEDGERENTRY][$ix1][$ix1][$key] = $inside;
                    }
                }
            }
        }
        if( empty( $this->accountId )) {
            $local[self::ACCOUNTID] = false;
        }
        if( is_null( $this->amount )) {
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
     * @param LedgerEntryTypesInterface $ledgerEntryType
     * @return static
     * @throws InvalidArgumentException
     */
    public function addLedgerEntryType( $key, LedgerEntryTypesInterface $ledgerEntryType ) {
        switch( true ) {
            case (( self::FOREIGNCURRENCYAMOUNT == $key ) && $ledgerEntryType instanceof ForeignCurrencyAmountType ) :
                if( ! empty( $this->previousElement )) {
                    $this->elementSetIndex += 1;
                }
                break;
            case (( self::OBJECTREFERENCE == $key ) &&  $ledgerEntryType instanceof ObjectReferenceType ) :
                if( in_array( $this->previousElement, self::$PREVIOUS23 )) {
                    $this->elementSetIndex += 1;
                }
                break;
            case (( self::SUBDIVIDEDACCOUNTOBJECTREFERENCE == $key ) &&
                $ledgerEntryType instanceof SubdividedAccountObjectReferenceType ) :
                if( in_array( $this->previousElement, self::$PREVIOUS23 )) {
                    $this->elementSetIndex += 1;
                }
                break;
            case (( self::ENTRYINFO == $key ) &&  $ledgerEntryType instanceof EntryInfoType ) :
                if( in_array( $this->previousElement, self::$PREVIOUS4 )) {
                    $this->elementSetIndex += 1;
                }
                break;
            case (( self::OVERSTRIKE == $key ) && $ledgerEntryType instanceof OverstrikeType ) :
                if( in_array( $this->previousElement, self::$PREVIOUS5 )) {
                    $this->elementSetIndex += 1;
                }
                break;
            case (( self::LOCKINGINFO == $key ) &&  $ledgerEntryType instanceof LockingInfoType ) :
                if( in_array( $this->previousElement, self::$PREVIOUS6 )) {
                    $this->elementSetIndex += 1;
                }
                break;
            default :
                throw new InvalidArgumentException(
                    sprintf( self::$FMTERR5, self::LEDGERENTRY, $key, get_class( $ledgerEntryType ))
                );
                break;
        } // end switch
        $this->ledgerEntryTypes[$this->elementSetIndex][] = [ $key => $ledgerEntryType ];
        $this->previousElement = $key;
        return $this;
    }

    /**
     * @return array
     */
    public function getLedgerEntryTypes() {
        return $this->ledgerEntryTypes;
    }

    /**
     * @param array $ledgerEntryTypes
     * @return static
     * @throws InvalidArgumentException
     */
    public function setLedgerEntryTypes( array $ledgerEntryTypes ) {
        foreach( $ledgerEntryTypes as $ix1 => $elementSet ) {
            if( ! is_array( $elementSet )) {
                $elementSet = [ $ix1 => $elementSet ];
            }
            foreach( $elementSet as $ix2 => $element ) {
                if( ! is_array( $element )) {
                    $element = [ $ix2 => $element ];
                }
                reset( $element );
                $key             = key( $element );
                $ledgerEntryType = current( $element );
                switch( true ) {
                    case (( self::FOREIGNCURRENCYAMOUNT == $key ) &&
                        $ledgerEntryType instanceof ForeignCurrencyAmountType ) :
                        break;
                    case (( self::OBJECTREFERENCE == $key ) &&  $ledgerEntryType instanceof ObjectReferenceType ) :
                        break;
                    case (( self::SUBDIVIDEDACCOUNTOBJECTREFERENCE == $key ) &&
                        $ledgerEntryType instanceof SubdividedAccountObjectReferenceType ) :
                        break;
                    case (( self::ENTRYINFO == $key ) &&  $ledgerEntryType instanceof EntryInfoType ) :
                        break;
                    case (( self::OVERSTRIKE == $key ) && $ledgerEntryType instanceof OverstrikeType ) :
                        break;
                    case (( self::LOCKINGINFO == $key ) &&  $ledgerEntryType instanceof LockingInfoType ) :
                        break;
                    default :
                        throw new InvalidArgumentException(
                            sprintf( self::$FMTERR52, self::LEDGERENTRY, $ix1, $ix2, $key, get_class( $ledgerEntryType ))
                        );
                        break;
                } // end switch
                $this->ledgerEntryTypes[$ix1][$ix2] = $element;
            }  // end foreach
        } // end foreach
        return $this;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAccountId( $accountId ) {
        $this->accountId = CommonFactory::assertAccountNumber( $accountId );
        return $this;
    }

    /**
     * @return string
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAmount( $amount ) {
        $this->amount = CommonFactory::assertAmount( $amount );
        return $this;
    }

    /**
     * @return string
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * @param string $quantity
     * @return static
     * @throws InvalidArgumentException
     */
    public function setQuantity( $quantity ) {
        $this->quantity = CommonFactory::assertString( $quantity );
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
     * @return static
     * @throws InvalidArgumentException
     */
    public function setText( $text ) {
        $this->text = CommonFactory::assertString( $text );
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLedgerDate() {
        return $this->ledgerDate;
    }

    /**
     * @param DateTime $ledgerDate
     * @return static
     */
    public function setLedgerDate( DateTime $ledgerDate ) {
        $this->ledgerDate = $ledgerDate;
        return $this;
    }


}