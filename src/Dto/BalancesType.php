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
use function is_array;
use function key;
use function reset;
use function sprintf;

class BalancesType extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var BaseBalanceType[]   [ *( key => OpeningBalance/ClosingBalance)]
     * @access private
     */
    private $balancesTypes = [];

    /**
     * @var int
     *            attribute name="accountId" type="sie:AccountNumber" use="optional"
     *            The account that the balances specify.
     *            If ommitted this element contains the balances for the object
     *            on the primary account of the subdivided account group.
     * @access private
     */
    private $accountId = null;


    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        foreach( array_keys( $this->balancesTypes ) as $ix ) {
            $inside = [];
            reset( $this->balancesTypes[$ix] );
            $key    = key( $this->balancesTypes[$ix] );
            if( ! $this->balancesTypes[$ix][$key]->isValid( $inside )) {
                $local[self::BALANCES][$ix][$key] = $inside;
            }
        }
        if( ! empty( $local )) {
            $expected[self::BALANCES] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param string $key
     * @param BaseBalanceType $balancesType
     * @return static
     * @throws InvalidArgumentException
     */
    public function addBalancesType( $key, BaseBalanceType $balancesType ) {
        switch( true ) {
            case (( self::OPENINGBALANCE == $key ) && $balancesType instanceof BaseBalanceType ) :
                break;
            case (( self::CLOSINGBALANCE == $key ) && $balancesType instanceof BaseBalanceType ) :
                break;
            default :
                throw new InvalidArgumentException(
                    sprintf( self::$FMTERR5, self::BASEBALANCE, $key, $balancesType )
                );
                break;
        } // end switch
        $this->balancesTypes[] = [ $key => $balancesType ];
        return $this;
    }

    /**
     * @return array
     */
    public function getBalancesTypes() {
        return $this->balancesTypes;
    }

    /**
     * @param array $balancesTypes
     * @return static
     * @throws InvalidArgumentException
     */
    public function setBalancesTypes( array $balancesTypes ) {
        foreach( $balancesTypes as $ix => $element ) {
            if( ! is_array( $element )) {
                $element = [ $ix => $element ];
            }
            reset( $element );
            $key          = key( $element );
            $balancesType = current( $element );
            switch( true ) {
                case (( self::OPENINGBALANCE == $key ) && $balancesType instanceof BaseBalanceType ) :
                    break;
                case (( self::CLOSINGBALANCE == $key ) && $balancesType instanceof BaseBalanceType ) :
                    break;
                default :
                    throw new InvalidArgumentException(
                        sprintf( self::$FMTERR51, self::BASEBALANCE, $ix, $key, $balancesType )
                    );
                    break;
            } // end switch
            $this->balancesTypes[$ix] = $element;
        } // end foreach
        return $this;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAccountId( $accountId ) {
        $this->accountId = CommonFactory::assertAccountNumber( $accountId );
        return $this;
    }



}