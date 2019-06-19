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

class ForeignCurrencyAmountType
    extends Sie5DtoBase
    implements BaseBalanceTypesInterface, LedgerEntryTypesInterface, LedgerEntryTypeEntriesInterface
{

    /**
     * @var string
     *            attribute name="amount" type="sie:Amount" use="required"
     *                <xsd:restriction base="xsd:decimal">
     *                    <xsd:fractionDigits value="2"/>
     *                </xsd:restriction>
     * @access private
     */
    private $amount = null;

    /**
     * @var string
     *            attribute name="currency" type="sie:Currency" use="required"
     *                <xsd:restriction base="xsd:string">
     *                    <xsd:pattern value="[A-Z][A-Z][A-Z]"/>
     *                </xsd:restriction>
     * @access private
     */
    private $currency = null;



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->amount )) {
            $local[self::AMOUNT] = false;
        }
        if( empty( $this->currency )) {
            $local[self::CURRENCY] = false;
        }
        if( ! empty( $local )) {
            $expected[self::FOREIGNCURRENCYAMOUNT] = $local;
            return false;
        }
        return true;
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
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return static
     * @throws InvalidArgumentException
     */
    public function setCurrency( $currency ) {
        $this->currency = CommonFactory::assertCurrency( $currency );
        return $this;
    }


}