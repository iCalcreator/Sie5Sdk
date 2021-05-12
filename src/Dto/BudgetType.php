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
use function gettype;
use function sprintf;

class BudgetType extends Sie5DtoBase implements AccountTypesInterface
{
    /**
     * @var ObjectReferenceType[]
     *
     * List of objects associated with this balance
     * minOccurs="0" maxOccurs="unbounded"
     */
    private $objectReference = [];

    /**
     * @var string
     *
     * Attribute name="month" type="xsd:gYearMonth"
     * If month is omitted the budget amount is for the full primary fiscal year.
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
     * @param string  $month
     * @param mixed   $amount
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
        foreach( array_keys( $this->objectReference ) as $ix1 ) { // element ix
            $inside = [];
            if( ! $this->objectReference[$ix1]->isValid( $inside )) {
                $local[self::OBJECTREFERENCE][$ix1] = $inside;
            }
        } // end foreach
        if( null == $this->amount ) {
            $local[self::AMOUNT] = false;
        }
        if( ! empty( $local )) {
            $expected[self::BUDGET] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param ObjectReferenceType $objectReference
     * @return static
     */
    public function addObjectReference( ObjectReferenceType $objectReference ) : self
    {
        $this->objectReference[] = $objectReference;
        return $this;
    }

    /**
     * @return ObjectReferenceType[]
     */
    public function getObjectReference() : array
    {
        return $this->objectReference;
    }

    /**
     * @param array $objectReference  ObjectReferenceType[]
     * @return static
     * @throws InvalidArgumentException
     */
    public function setObjectReference( array $objectReference ) : self
    {
        foreach( $objectReference as $ix => $value ) {
            if( $value instanceof ObjectReferenceType ) {
                $this->objectReference[] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::OBJECTREFERENCE, $ix, $type ));
            }
        } // end foreach
        return $this;
    }

    /**
     * @return string
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
     */
    public function setQuantity( $quantity ) : self
    {
        $this->quantity = (float) $quantity;
        return $this;
    }
}
