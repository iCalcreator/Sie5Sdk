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

use function gettype;
use function sprintf;

abstract class SubdividedAccountObjectType extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var array   [ *BalancesType ] minOccurs="0" maxOccurs="unbounded"
     * @access protected
     */
    protected $balances = [];

    /**
     * @var OriginalAmountType     maxOccurs="1"  minOccurs="1"
     * @access protected
     */
    protected $originalAmount = null;

    /**
     * @var string
     *            attribute name="id" type="xsd:string" use="required"
     *            Item identifier
     * @access protected
     */
    protected $id = null;

    /**
     * @var string
     *            attribute name="name" type="xsd:string" use="optional"
     *            Item name
     * @access protected
     */
    protected $name = null;

    use ExtensionAttributeTrait;

    /**
     * @param BalancesType $balances
     * @return static
     */
    public function addBalances( BalancesType $balances ) {
        $this->balances[] = $balances;
        return $this;
    }

    /**
     * @return array  [ *BalancesType ]
     */
    public function getBalances() {
        return $this->balances;
    }

    /**
     * @param array $balances  *BalancesType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setBalances( array $balances ) {
        foreach( $balances as $ix => $value ) {
            if( $value instanceof BalancesType ) {
                $this->balances[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::BALANCES, $ix, $type ));
            }
        }
        return $this;
    }

    /**
     * @return OriginalAmountType
     */
    public function getOriginalAmount() {
        return $this->originalAmount;
    }

    /**
     * @param OriginalAmountType $originalAmount
     * @return static
     */
    public function setOriginalAmount( OriginalAmountType $originalAmount ) {
        $this->originalAmount = $originalAmount;
        return $this;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     * @throws InvalidArgumentException
     */
    public function setId( $id ) {
        $this->id = CommonFactory::assertString( $id );
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     * @throws InvalidArgumentException
     */
    public function setName( $name ) {
        $this->name = CommonFactory::assertString( $name );
        return $this;
    }

}