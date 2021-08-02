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

class BaseBalanceType extends Sie5DtoBase implements AccountTypesInterface
{
    /**
     * @var array
     *
     * maxOccurs="unbounded" minOccurs="0"
     * Sequences of one OR both
     *  ForeignCurrencyAmount - ForeignCurrencyAmountType
     *  ObjectReference       - ObjectReferenceType
     */
    private $baseBalanceTypes = [];

    /**
     * @var string
     */
    private $previousElement  = null;

    /**
     * @var int
     */
    private $elementSetIndex  = 0;

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
     * @param array $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null ) : bool
    {
        $local  = [];
        $inside = [];
        foreach( array_keys( $this->baseBalanceTypes ) as $x1 ) { // elementSet ix
            $inside[$x1] = [];
            foreach( array_keys( $this->baseBalanceTypes[$x1] ) as $x2 ) { // element ix
                $inside[$x1][$x2] = [];
                foreach( array_keys( $this->baseBalanceTypes[$x1][$x2] ) as $key ) {
                    if( $this->baseBalanceTypes[$x1][$x2][$key]->isValid(
                        $inside[$x1][$x2] )
                    ) {
                        unset( $inside[$x1][$x2] );
                    }
                } // end foreach
                if( empty( $inside[$x1][$x2] )) {
                    unset( $inside[$x1][$x2] );
                }
            } // end foreach
            if( empty( $inside[$x1] )) {
                unset( $inside[$x1] );
            }
        } // end foreach
        if( ! empty( $inside )) {
            $key         = self::getClassPropStr( self::class, self::BASEBALANCE );
            $local[$key] = $inside;
        } // end if
        if( null === $this->month ) {
            $local[] = self::errMissing(self::class, self::MONTH );
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
     * Add single (typed) BaseBalanceTypesInterface
     *
     * Type : FOREIGNCURRENCYAMOUNT / OBJECTREFERENCE
     *
     * @param string $key
     * @param BaseBalanceTypesInterface $baseBalanceType
     * @return static
     * @throws InvalidArgumentException
     */
    public function addBaseBalanceType( string $key, BaseBalanceTypesInterface $baseBalanceType ) : self
    {
        switch( true ) {
            case (( self::FOREIGNCURRENCYAMOUNT == $key ) &&
                $baseBalanceType instanceof ForeignCurrencyAmountType ) :
                if( ! empty( $this->previousElement )) {
                    $this->elementSetIndex += 1;
                }
                break;
            case (( self::OBJECTREFERENCE == $key ) &&
                $baseBalanceType instanceof ObjectReferenceType ) :
                if( self::OBJECTREFERENCE == $this->previousElement ) {
                    $this->elementSetIndex += 1;
                }
                break;
            default :
                throw new InvalidArgumentException(
                    sprintf( self::$FMTERR5, self::BASEBALANCE, $key, get_class( $baseBalanceType ))
                );
        } // end switch
        $this->baseBalanceTypes[$this->elementSetIndex][] = [ $key => $baseBalanceType ];
        $this->previousElement = $key;
        return $this;
    }

    /**
     * @return array
     */
    public function getBaseBalanceTypes() : array
    {
        return $this->baseBalanceTypes;
    }

    /**
     * Set BaseBalanceTypes, array, ( *( type => BaseBalanceTypesInterface ) = / BaseBalanceTypesInterface[]
     *
     * Type : FOREIGNCURRENCYAMOUNT / OBJECTREFERENCE
     *
     * @param array $baseBalanceTypes
     * @return static
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function setBaseBalanceTypes( array $baseBalanceTypes ) : self
    {
        foreach( $baseBalanceTypes as $ix1 => $elementSet ) {
            if( ! is_array( $elementSet )) {
                $elementSet = [ $ix1 => $elementSet ];
            }
            foreach( $elementSet as $ix2 => $element ) {
                switch( true ) {
                    case is_array( $element ) :
                        reset( $element );
                        $key = key( $element );
                        $this->addBaseBalanceType( $key, current( $element ));
                        break;
                    case ( $element instanceof ForeignCurrencyAmountType ) :
                        $this->addBaseBalanceType( self::FOREIGNCURRENCYAMOUNT, $element );
                        break;
                    case ( $element instanceof ObjectReferenceType ) :
                        $this->addBaseBalanceType( self::OBJECTREFERENCE, $element );
                        break;
                    default :
                        $this->addBaseBalanceType( $ix2, $element );
                        break;
                } // end switch
            } // end foreach
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
