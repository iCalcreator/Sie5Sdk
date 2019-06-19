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
use function gettype;
use function sprintf;

class BudgetMultidimType extends Sie5DtoBase implements AccountTypesInterface
{

    /**
     * @var array
     *                      choice of
     *                        2-unbound  ObjectReferenceType
     *                      List of objects associated with this balance
     * @access private
     */
    private $budgetMultidimTypes = [];

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
        foreach( array_keys( $this->budgetMultidimTypes ) as $ix ) {  // element ix
            $inside = [];
            if( ! $this->budgetMultidimTypes[$ix]->isValid( $inside )) {
                $local[self::BUDGETMULTIDIM][$ix][self::OBJECTREFERENCE] = $inside;
            }
        }
        if( empty( $this->month )) {
            $local[self::MONTH] = false;
        }
        if( is_null( $this->amount )) {
            $local[self::AMOUNT] = false;
        }
        if( ! empty( $local )) {
            $expected[self::BUDGETMULTIDIM] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param ObjectReferenceType $budgetMultidimType
     * @return static
     * @throws InvalidArgumentException
     */
    public function addBudgetMultidimType( ObjectReferenceType $budgetMultidimType ) {
        $this->budgetMultidimTypes[] = $budgetMultidimType;
        return $this;
    }

    /**
     * @return array
     */
    public function getBudgetMultidimTypes() {
        return $this->budgetMultidimTypes;
    }

    /**
     * @param array $budgetMultidimTypes  - ObjectReferenceType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setBudgetMultidimTypes( array $budgetMultidimTypes ) {
        $cnt = 0;
        foreach( $budgetMultidimTypes as $ix => $value ) {
            if( $value instanceof ObjectReferenceType ) {
                $this->budgetMultidimTypes[$ix] = $value;
                $cnt += 1;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::BUDGETMULTIDIM, $ix, $type ));
            }
        }
        if( 1 == $cnt ) {
            throw new InvalidArgumentException(
                sprintf( self::$FMTERR3, self::BUDGETMULTIDIM, self::OBJECTREFERENCE )
            );
        }
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