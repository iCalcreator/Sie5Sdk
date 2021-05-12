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
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

use function array_keys;
use function current;
use function get_class;
use function is_array;
use function key;
use function reset;
use function sprintf;

class BaseBalanceMultidimType extends Sie5DtoBase implements AccountTypesInterface
{
    /**
     * @var array
     *
     * maxOccurs="unbounded" minOccurs="0"
     * Sequence of one OR both
     *   ForeignCurrencyAmount - 0-1        ForeignCurrencyAmountType
     *   ObjectReference       - 2-unbound  ObjectReferenceType
     * List of objects associated with this balance
     */
    private $baseBalanceMultidimTypes = [];

    /**
     * @var string
     */
    private $previousElement          = null;

    /**
     * @var int
     */
    private $elementSetIndex          = 0;

    /**
     * @var string
     *
     * Attribute name="month" type="xsd:gYearMonth" use="required" ( '2001-10')
     */
    private $month = null;

    /**
     * @var float
     *
     * Attribute name="amount" type="sie:Amount" use="required"
     * Amount. Positive for debit, negative for credit.
     */
    private $amount = null;

    /**
     * @var float
     *
     * Attribute name="quantity" type="xsd:decimal"
     */
    private $quantity = null;

    /**
     * Factory method, set month and amount
     *
     * @param string $month
     * @param mixed  $amount
     * @return static
     * @throws InvalidArgumentException
     */
    public static function factoryMonthAmount( string $month, $amount ) : self
    {
        return self::factory()
                   ->setMonth( $month )
                   ->setAmount( $amount );
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
        foreach( array_keys( $this->baseBalanceMultidimTypes ) as $ix1 ) { // elementSet ix
            foreach( array_keys( $this->baseBalanceMultidimTypes[$ix1] ) as $ix2 ) { // element ix
                foreach( array_keys( $this->baseBalanceMultidimTypes[$ix1][$ix2] ) as $key ) {
                    $inside = [];
                    if( ! $this->baseBalanceMultidimTypes[$ix1][$ix2][$key]->isValid( $inside )) {
                        $local[self::BASEBALANCEMULTIDIM][$ix1][$ix2][$key] = $inside;
                    }
                }
            } // end foreach
        } // end foreach
        if( empty( $this->month )) {
            $local[self::MONTH] = false;
        }
        if( null == $this->amount ) {
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
    public function addBaseBalanceMultidimType( string $key, BaseBalanceTypesInterface $baseBalanceMultidimType ) : self
    {
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
    public function getBaseBalanceMultidimTypes() : array
    {
        return $this->baseBalanceMultidimTypes;
    }

    /**
     * @param array $baseBalanceMultidimTypes
     * @return static
     * @throws InvalidArgumentException
     */
    public function setBaseBalanceMultidimTypes( array $baseBalanceMultidimTypes ) : self
    {
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
     * @return null|string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param string $month
     * @return static
     * @throws InvalidArgumentException
     */
    public function setMonth( string $month ) : self
    {
        $this->month = CommonFactory::assertGYearMonth( $month );
        return $this;
    }

    /**
     * @return null|float
     */
    public function getAmount()
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
     * @return null|float
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
}
