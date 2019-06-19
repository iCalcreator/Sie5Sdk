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

use function array_keys;
use function sprintf;

class AccountAggregationsType extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var array   [ *AccountAggregationType ]
     *
     * Container element for individual accounts
     * @access private
     */
    private $accountAggregation = [];



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->accountAggregation )) {
            $local[self::ACCOUNTAGGREGATIONS] = false;
        }
        else {
            foreach( array_keys( $this->accountAggregation ) as $ix ) {
                $inside = [];
                if( ! $this->accountAggregation[$ix]->isValid( $inside )) {
                    $local[$ix] = $inside;
                }
            }
        }
        if( ! empty( $local )) {
            $expected[self::ACCOUNTAGGREGATIONS] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param AccountAggregationType $accountAggregation
     * @return static
     */
    public function addAccountAggregation( AccountAggregationType $accountAggregation ) {
        $this->accountAggregation[] = $accountAggregation;
        return $this;
    }

    /**
     * @return array
     */
    public function getAccountAggregation() {
        return $this->accountAggregation;
    }

    /**
     * @param array $accountAggregation   *AccountAggregationType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAccountAggregation( array $accountAggregation ) {
        foreach( $accountAggregation as $ix => $value) {
            if( $value instanceof AccountAggregationType ) {
                $this->accountAggregation[$ix] = $value;
            }
            else {
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::ACCOUNTAGGREGATIONS, $ix, self::ACCOUNTAGGREGATION ));
            }
        }
        return $this;
    }



}