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
use TypeError;

use function array_keys;
use function array_search;
use function sprintf;
use function asort;

/**
 * Class AccountsTypeEntry
 */
class AccountsTypeEntry extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var AccountTypeEntry[]  minOccurs="0" maxOccurs="unbounded"
     *
     * Container element for individual accounts
     */
    private array $account = [];

    /**
     * Return bool true is instance is valid
     *
     * @param null|array $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( ! empty( $this->account )) {
            $inside = [];
            foreach( array_keys( $this->account ) as $ix ) {
                $inside[$ix] = [];
                if( $this->account[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::ACCOUNT );
                $local[$key] = $inside;
            }
        } // end if
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single AccountTypeEntry
     *
     * @param AccountTypeEntry $account
     * @return static
     * @throws InvalidArgumentException
     */
    public function addAccount( AccountTypeEntry $account ) : self
    {
        if( $account->isValid() &&
            ( true !== $this->isAccountIdUnique( $account->getId()))) {
            throw new InvalidArgumentException(
                sprintf( self::$FMTERR11, self::ACCOUNT, self::ID, $account->getId())
            );
        }
        $this->account[] = $account;
        return $this;
    }

    /**
     * Return AccountTypeEntry if (AccountTypeEntry-)id given (bool false on not found) otherwise array all
     *
     * @param null|string $id
     * @return array|AccountTypeEntry|bool   if id given AccountTypeEntry, bool false on not found otherwise array
     */
    public function getAccount( ? string $id = null ) : AccountTypeEntry | array | bool
    {
        if( ! empty( $id )) {
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
    public function getAllAccountIds() : array
    {
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
     * @param string $id
     * @return bool|int|string  AccountTypeEntry index or true if not found i.e. unique
     */
    public function isAccountIdUnique( string $id ) : bool | int | string
    {
        $hitIx = array_search( $id, $this->getAllAccountIds(), true );
        return ( false !== $hitIx ) ? $hitIx : true;
    }

    /**
     * Set AccountTypeEntry's, array
     *
     * @param AccountTypeEntry[] $accounts
     * @return static
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function setAccount( array $accounts ) : self
    {
        foreach( $accounts as $account ) {
            $this->addAccount( $account );
        } // end foreach
        return $this;
    }
}
