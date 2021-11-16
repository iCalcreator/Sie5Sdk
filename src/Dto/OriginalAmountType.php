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

use DateTime;
use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

class OriginalAmountType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var ForeignCurrencyAmountType|null
     *
     * MaxOccurs="1" minOccurs="0"
     */
    private ?ForeignCurrencyAmountType $foreignCurrencyAmount = null;

    /**
     * @var DateTime|null
     *
     * Attribute name="date" type="xsd:date" use="required"
     */
    private ?DateTime $date;

    /**
     * @var float|null
     *
     * Amount. Positive for debit, negative for credit
     * Attribute type="sie:Amount" use="required"
     */
    private ?float $amount = null;

    /**
     * Factory method, set date and amount
     *
     * @param DateTime $date
     * @param mixed    $amount
     * @return static
     * @throws InvalidArgumentException
     */
    public static function factoryDateAmount( DateTime $date, $amount ) : self
    {
        return self::factory()
            ->setDate( $date )
            ->setAmount( $amount );
    }

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        parent:: __construct();
        $this->date = new DateTime();
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
        if( empty( $this->date )) {
            $local[] = self::errMissing(self::class, self::DATE );
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
     * @return null|ForeignCurrencyAmountType
     */
    public function getForeignCurrencyAmount() : ?ForeignCurrencyAmountType
    {
        return $this->foreignCurrencyAmount;
    }

    /**
     * @param ForeignCurrencyAmountType $foreignCurrencyAmount
     * @return static
     */
    public function setForeignCurrencyAmount( ForeignCurrencyAmountType $foreignCurrencyAmount ) : self
    {
        $this->foreignCurrencyAmount = $foreignCurrencyAmount;
        return $this;
    }

    /**
     * @return null|DateTime
     */
    public function getDate() : ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return static
     */
    public function setDate( DateTime $date ) : self
    {
        $this->date = $date;
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
    public function setAmount( $amount ) : self
    {
        $this->amount = CommonFactory::assertAmount( $amount );
        return $this;
    }
}
