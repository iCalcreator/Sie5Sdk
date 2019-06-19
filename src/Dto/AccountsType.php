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
use function array_search;
use function get_class;
use function gettype;
use function is_null;
use function sprintf;
use function asort;

class AccountsType extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var array  *AccountType
     *
     * Container element for individual accounts
     * @access private
     */
    private $account = [];



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( ! empty( $this->account )) {
            foreach( array_keys( $this->account ) as $ix ) {
                $inside = [];
                if( ! $this->account[$ix]->isValid( $inside )) {
                    $local[self::ACCOUNT][$ix] = $inside;
                }
            }
        }
        if( ! empty( $local )) {
            $expected[self::ACCOUNTS] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param AccountType $account
     * @return static
     * @throws InvalidArgumentException
     */
    public function addAccount( AccountType $account ) {
        if( true !== $this->isAccountIdUnique( $account->getId())) {
            throw new InvalidArgumentException(
                sprintf( self::$FMTERR11, self::ACCOUNT, self::ID, $account->getId() )
            );
        }
        $this->account[] = $account;
        return $this;
    }

    /**
     * Return AccountType if (AccountType-)id (AccountNumber) given (bool false on not found) otherwise array all
     *
     * @param int $id
     * @return array|AccountType|bool   if id given AccountType, bool false on not found otherwise array all
     */
    public function getAccount( $id = null ) {
        if( ! is_null( $id )) {
            $ix = $this->isAccountIdUnique( $id );
            return ( true !== $ix ) ? $this->account[$ix] : false;
        }
        return $this->account;
    }

    /**
     * Return array AccountsIds
     *
     * @return array
     */
    public function getAllAccountIds() {
        $accountIds = [];
        foreach( array_keys( $this->account ) as $ix ) {
            $accountIds[$ix] = $this->account[$ix]->getId();
        }
        asort( $accountIds );
        return $accountIds;
    }

    /**
     * Return int index if Account id is set or bool true if not
     *
     * @param int $id
     * @return int|bool  AccountType index or true if not found
     */
    public function isAccountIdUnique( $id ) {
        $hitIx = array_search( $id, $this->getAllAccountIds());
        return ( false !== $hitIx ) ? $hitIx : true;
    }

    /**
     * @param array $accounts  [ *AccountType ]
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAccount( array $accounts ) {
        foreach( $accounts as $ix => $account ) {
            switch( true ) {
                case ( ! $account instanceof AccountType ) :
                    $type = gettype( $account );
                    if( self::$OBJECT == $type ) {
                        $type = get_class( $account );
                    }
                    throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::ACCOUNTS, $ix, $type ));
                    break;
                case ( true !== $this->isAccountIdUnique( $account->getId())) :
                    throw new InvalidArgumentException(
                        sprintf( self::$FMTERR111, self::ACCOUNT, self::ID, $ix, $account->getId())
                    );
                    break;
                default :
                    $this->account[$ix] = $account;
                    break;
            } // end switch
        } // end foreach
        return $this;
    }



}