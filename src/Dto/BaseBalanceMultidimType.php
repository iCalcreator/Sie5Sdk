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

class BaseBalanceMultidimType extends Sie5DtoBase implements AccountTypesInterface
{
    /**
     * @var array
     *
     * maxOccurs="unbounded" minOccurs="0"
     * Sequences of
     *   ForeignCurrencyAmount - 0-1        ForeignCurrencyAmountType
     *   ObjectReference       - 2-unbound  ObjectReferenceType
     * List of objects associated with this balance
     */
    private array $baseBalanceMultidimTypes = [];

    /**
     * @var string|null
     */
    private ?string $previousElement          = null;

    /**
     * @var int
     */
    private int $elementSetIndex          = 0;

    /**
     * @var string|null
     *
     * Attribute name="month" type="xsd:gYearMonth" use="required" ( '2001-10')
     */
    private ?string $month = null;

    /**
     * @var float|null
     *
     * Attribute name="amount" type="sie:Amount" use="required"
     * Amount. Positive for debit, negative for credit.
     */
    private ?float $amount = null;

    /**
     * @var float|null
     *
     * Attribute name="quantity" type="xsd:decimal"
     */
    private ?float $quantity = null;

    /**
     * Factory method, set month and amount
     *
     * @param string $month
     * @param mixed  $amount
     * @return static
     * @throws InvalidArgumentException
     */
    public static function factoryMonthAmount( string $month, mixed $amount ) : self
    {
        return self::factory()
                   ->setMonth( $month )
                   ->setAmount( $amount );
    }

    /**
     * Return bool true is instance is valid
     *
     * @param null|array $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local  = [];
        $inside = [];
        foreach( array_keys( $this->baseBalanceMultidimTypes ) as $x1 ) { // elementSet x1
            $inside[$x1] = [];
            foreach( array_keys( $this->baseBalanceMultidimTypes[$x1] ) as $x2 ) { // keyed element x2
                $inside[$x1][$x2] = [];
                foreach( array_keys( $this->baseBalanceMultidimTypes[$x1][$x2] ) as $key ) {
                    if( $this->baseBalanceMultidimTypes[$x1][$x2][$key]->isValid(
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
            $key         = self::getClassPropStr( self::class, self::BASEBALANCEMULTIDIM );
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
     * @param string $key
     * @param BaseBalanceTypesInterface $baseBalanceMultidimType
     * @return static
     * @throws InvalidArgumentException
     */
    public function addBaseBalanceMultidimType(
        string $key,
        BaseBalanceTypesInterface $baseBalanceMultidimType
    ) : self
    {
        switch( true ) {
            case (( self::FOREIGNCURRENCYAMOUNT === $key ) &&
                $baseBalanceMultidimType instanceof ForeignCurrencyAmountType ) :
                if( ! empty( $this->previousElement )) {
                    ++$this->elementSetIndex;
                }
                break;
            case (( self::OBJECTREFERENCE === $key ) &&
                $baseBalanceMultidimType instanceof ObjectReferenceType ) :
                break;
            default :
                throw new InvalidArgumentException(
                    sprintf(
                        self::$FMTERR5,
                        self::BASEBALANCEMULTIDIM,
                        $key,
                        get_class( $baseBalanceMultidimType )
                    )
                );
        } // end switch
        $this->baseBalanceMultidimTypes[$this->elementSetIndex][] =
            [ $key => $baseBalanceMultidimType ];
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
     * Set baseBalanceMultidimTypes, array ( *( type => BaseBalanceTypesInterface )) / BaseBalanceTypesInterface[]
     *
     * Type : FOREIGNCURRENCYAMOUNT / OBJECTREFERENCE
     *
     * @param array $baseBalanceMultidimTypes
     * @return static
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function setBaseBalanceMultidimTypes( array $baseBalanceMultidimTypes ) : self
    {
        $cnt = 0;
        foreach( $baseBalanceMultidimTypes as $ix1 => $elementSet ) {
            if( ! is_array( $elementSet )) {
                $elementSet = [ $ix1 => $elementSet ];
            }
            foreach( $elementSet as $ix2 => $element ) {
                switch( true ) {
                    case is_array( $element ) :
                        reset( $element );
                        $key = (string) key( $element );
                        $this->addBaseBalanceMultidimType( $key, current( $element ));
                        if( self::OBJECTREFERENCE === $key ) {
                            ++$cnt;
                        }
                        break;
                    case ( $element instanceof ForeignCurrencyAmountType ) :
                        $this->addBaseBalanceMultidimType(
                            self::FOREIGNCURRENCYAMOUNT,
                            $element
                        );
                        break;
                    case ( $element instanceof ObjectReferenceType ) :
                        $this->addBaseBalanceMultidimType(
                            self::OBJECTREFERENCE,
                            $element
                        );
                        ++$cnt;
                        break;
                    default :
                        $this->addBaseBalanceMultidimType((string) $ix2, $element );
                        if( self::OBJECTREFERENCE === $ix2 ) {
                            ++$cnt;
                        }
                        break;
                } // end switch
            } // end foreach
        } // end foreach
        if( 1 === $cnt ) {
            throw new InvalidArgumentException(
                sprintf( self::$FMTERR3, self::BASEBALANCEMULTIDIM, self::OBJECTREFERENCE )
            );
        }
        return $this;
    }

    /**
     * @return null|string
     */
    public function getMonth() : ?string
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
}
