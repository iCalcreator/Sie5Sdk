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
use function get_class;
use function in_array;
use function is_array;
use function key;
use function reset;
use function sprintf;

class BalancesType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var array   [ *( key => OpeningBalance/ClosingBalance)]
     */
    private array $balancesTypes = [];

    /**
     * @var string|null
     *
     * Attribute name="accountId" type="sie:AccountNumber" use="optional"
     * The account that the balances specify.
     * If ommitted this element contains the balances for the object
     * on the primary account of the subdivided account group.
     */
    private ?string $accountId = null;


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
        foreach( array_keys( $this->balancesTypes ) as $ix ) {
            $inside[$ix] = [];
            reset( $this->balancesTypes[$ix] );
            $key    = key( $this->balancesTypes[$ix] );
            if( $this->balancesTypes[$ix][$key]->isValid( $inside[$ix] )) {
                unset( $inside[$ix] );
            }
        } // end foreach
        if( ! empty( $inside )) {
            $key         = self::getClassPropStr( self::class, self::BALANCES );
            $local[$key] = $inside;
        } // end if
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single (typed) BaseBalanceType
     *
     * @param string $key
     * @param BaseBalanceType $balancesType
     * @return static
     * @throws InvalidArgumentException
     */
    public function addBalancesType( string $key, BaseBalanceType $balancesType ) : self
    {
        static $KEYTYPES = [ self::OPENINGBALANCE, self::CLOSINGBALANCE ];
        if( ! in_array( $key, $KEYTYPES, true ) ) {
            throw new InvalidArgumentException(
                sprintf( self::$FMTERR5, self::BASEBALANCE, $key, get_class( $balancesType ))
            );
        }
        $this->balancesTypes[] = [ $key => $balancesType ];
        return $this;
    }

    /**
     * @return array
     */
    public function getBalancesTypes() : array
    {
        return $this->balancesTypes;
    }

    /**
     * Set BaseBalanceTypes, array ( *( type => BaseBalanceType )) / ( type => BaseBalanceType )
     *
     * Type OPENINGBALANCE / CLOSINGBALANCE
     *
     * @param array $balancesTypes
     * @return static
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function setBalancesTypes( array $balancesTypes ) : self
    {
        foreach( $balancesTypes as $ix => $element ) {
            if( ! is_array( $element )) {
                $element = [ $ix => $element ];
            }
            reset( $element );
            $key = (string) key( $element );
            $this->addBalancesType( $key, current( $element ));
        } // end foreach
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAccountId() : ?string
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAccountId( string $accountId ) : self
    {
        $this->accountId = CommonFactory::assertAccountNumber( $accountId );
        return $this;
    }
}
