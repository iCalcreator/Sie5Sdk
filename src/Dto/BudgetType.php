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

class BudgetType extends Sie5DtoBase implements AccountTypesInterface
{
    /**
     * @var ObjectReferenceType[]
     *
     * List of objects associated with this balance
     * minOccurs="0" maxOccurs="unbounded"
     */
    private array $objectReference = [];

    /**
     * @var string|null
     *
     * Attribute name="month" type="xsd:gYearMonth"
     * If month is omitted the budget amount is for the full primary fiscal year.
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
     * @param mixed $amount
     * @return static
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
        foreach( array_keys( $this->objectReference ) as $ix ) { // element ix
            $inside[$ix] = [];
            if( $this->objectReference[$ix]->isValid( $inside[$ix] )) {
                unset( $inside[$ix] );
            }
        } // end foreach
        if( ! empty( $inside )) {
            $key         = self::getClassPropStr( self::class, self::OBJECTREFERENCE );
            $local[$key] = $inside;
        } // end if
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
     * Add single ObjectReferenceType
     *
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
     * Set ObjectReferenceTypes, array
     *
     * @param ObjectReferenceType[] $objectReference
     * @return static
     * @throws TypeError
     */
    public function setObjectReference( array $objectReference ) : self
    {
        foreach( $objectReference as $value ) {
            $this->addObjectReference( $value );
        } // end foreach
        return $this;
    }

    /**
     * @return string|null
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
     * @return float|null
     */
    public function getQuantity() : ?float
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return static
     */
    public function setQuantity( mixed $quantity ) : self
    {
        $this->quantity = (float) $quantity;
        return $this;
    }
}
