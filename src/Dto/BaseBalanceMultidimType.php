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

class BaseBalanceMultidimType extends Sie5DtoBase implements AccountTypesInterface
{

    /**
     * @var array
     *                      sequence of one OR both
     *                          ForeignCurrencyAmount - 0-1        ForeignCurrencyAmountType
     *                          ObjectReference       - 2-unbound  ObjectReferenceType
     *                      List of objects associated with this balance
     * @access private
     */
    private $baseBalanceMultidimTypes = [];
    private $previousElement          = null;
    private $elementSetIndex          = 0;

    /**
     * @var string
     *            attribute name="month" type="xsd:gYearMonth" use="required" ( '2001-10')
     * @access private
     */
    private $month = null;

    /**
     * @var string
     *          attribute name="amount" type="sie:Amount" use="required"
     *          Amount. Positive for debit, negative for credit.
     * @access private
     */
    private $amount = null;

    /**
     * @var string
     *          attribute name="quantity" type="xsd:decimal"
     * @access private
     */
    private $quantity = null;


    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        foreach( array_keys( $this->baseBalanceMultidimTypes ) as $ix1 ) { // elementSet ix
            foreach( array_keys( $this->baseBalanceMultidimTypes[$ix1] ) as $ix2 ) { // element ix
                foreach( array_keys( $this->baseBalanceMultidimTypes[$ix1][$ix2] ) as $key ) {
                    $inside = [];
                    if( ! $this->baseBalanceMultidimTypes[$ix1][$ix2][$key]->isValid( $inside )) {
                        $local[self::BASEBALANCEMULTIDIM][$ix1][$ix2][$key] = $inside;
                    }
                }
            }
        }
        if( empty( $this->month )) {
            $local[self::MONTH] = false;
        }
        if( is_null( $this->amount )) {
            $local[self::AMOUNT] = false;
        }
        if( ! empty( $local )) {
            $expected[self::BASEBALANCEMULTIDIM] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param string $key
     * @param BaseBalanceTypesInterface $baseBalanceMultidimType
     * @return static
     * @throws InvalidArgumentException
     */
    public function addBaseBalanceMultidimType( $key, BaseBalanceTypesInterface $baseBalanceMultidimType ) {
        switch( true ) {
            case (( self::FOREIGNCURRENCYAMOUNT == $key ) &&
                $baseBalanceMultidimType instanceof ForeignCurrencyAmountType ) :
                if( ! empty( $this->previousElement )) {
                    $this->elementSetIndex += 1;
                }
                break;
            case (( self::OBJECTREFERENCE == $key ) &&
                $baseBalanceMultidimType instanceof ObjectReferenceType ) :
                break;
            default :
                throw new InvalidArgumentException(
                    sprintf( self::$FMTERR5, self::BASEBALANCEMULTIDIM, $key, get_class( $baseBalanceMultidimType ))
                );
                break;
        } // end switch
        $this->baseBalanceMultidimTypes[$this->elementSetIndex][] = [ $key => $baseBalanceMultidimType ];
        $this->previousElement = $key;
        return $this;
    }

    /**
     * @return array
     */
    public function getBaseBalanceMultidimTypes() {
        return $this->baseBalanceMultidimTypes;
    }

    /**
     * @param array $baseBalanceMultidimTypes
     * @return static
     * @throws InvalidArgumentException
     */
    public function setBaseBalanceMultidimTypes( array $baseBalanceMultidimTypes ) {
        foreach( $baseBalanceMultidimTypes as $ix1 => $elementSet ) {
            if( ! is_array( $elementSet )) {
                $elementSet = [ $ix1 => $elementSet ];
            }
            $cnt = 0;
            foreach( $elementSet as $ix2 => $element ) {
                if( ! is_array( $element )) {
                    $element = [ $ix2 => $element ];
                }
                reset( $element );
                $key = key( $element );
                $baseBalanceMultidimType = current( $element );
                switch( true ) {
                    case (( self::FOREIGNCURRENCYAMOUNT == $key ) &&
                        $baseBalanceMultidimType instanceof ForeignCurrencyAmountType ) :
                        break;
                    case (( self::OBJECTREFERENCE == $key ) &&
                        $baseBalanceMultidimType instanceof ObjectReferenceType ) :
                        $cnt += 1;
                        break;
                    default :
                        throw new InvalidArgumentException(
                            sprintf(
                                self::$FMTERR52,
                                self::BASEBALANCEMULTIDIM,
                                $ix1,
                                $ix2,
                                $key,
                                get_class( $baseBalanceMultidimType )
                            )
                        );
                        break;
                } // end switch
                $this->baseBalanceMultidimTypes[$ix1][$ix2] = $element;
            } // end foreach
            if( 1 == $cnt ) {
                throw new InvalidArgumentException(
                    sprintf( self::$FMTERR4, self::BASEBALANCEMULTIDIM, $ix1, self::OBJECTREFERENCE )
                );
            }
        } // end foreach
        return $this;
    }

    /**
     * @return string
     */
    public function getMonth() {
        return $this->month;
    }

    /**
     * @param string $month
     * @return static
     * @throws InvalidArgumentException
     */
    public function setMonth( $month ) {
        $this->month = CommonFactory::assertGYearMonth( $month );
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

}