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

class OriginalAmountType extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var ForeignCurrencyAmountType
     * @access private
     */
    private $foreignCurrencyAmount = null;

    /**
     * @var DateTime
     *            attribute name="date" type="xsd:date" use="required"
     * @access private
     */
    private $date = null;

    /**
     * @var float
     *            Amount. Positive for debit, negative for credit
     * @access private
     */
    private $amount = null;

    /**
     * Class constructor
     *
     */
    public function __construct() {
        parent::__Construct();
        $this->date = new DateTime();
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->date )) {
            $local[self::DATE] = false;
        }
        if( is_null( $this->amount )) {
            $local[self::AMOUNT] = false;
        }
        if( ! empty( $local )) {
            $expected[self::ORIGINALAMOUNT] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return ForeignCurrencyAmountType
     */
    public function getForeignCurrencyAmount() {
        return $this->foreignCurrencyAmount;
    }

    /**
     * @param ForeignCurrencyAmountType $foreignCurrencyAmount
     * @return static
     */
    public function setForeignCurrencyAmount( ForeignCurrencyAmountType $foreignCurrencyAmount ) {
        $this->foreignCurrencyAmount = $foreignCurrencyAmount;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return static
     */
    public function setDate( DateTime $date ) {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAmount( $amount ) {
        $this->amount = CommonFactory::assertAmount( $amount );
        return $this;
    }

}