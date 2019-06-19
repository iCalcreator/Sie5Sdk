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
use function sprintf;

class AccountTypeEntry extends Sie5DtoExtAttrBase
{
    /**
     * @var array  [ *BudgetType ]
     * @access private
     */
    private $budget = [];

    /**
     * @var int
     *          attribute name="id" type="sie:AccountNumber" use="required"
     *          pattern value="[0-9]+"
     *          Unique account identifier. The account number.
     * @access private
     */
    private $id = null;

    /**
     * @var string
     *          attribute name="name" type="xsd:string" use="required"
     *          Account name
     * @access private
     */
    private $name = null;

    /**
     * @var string
     *          attribute name="type" use="required"
     *          enumeration : "asset", "liability", "equity", "cost", "income", "statistics"
     * @access private
     */
    private $type = null;

    /**
     * @var string[]
     * @static
     */
    public static $typeEnumeration = [
        self::ASSET, self::LIABILITY, self::EQUITY, self::COST, self::INCOME, self::STATISTICS
    ];

    /**
     * @var string
     *            attribute name="unit" type="xsd:string"
     *            Unit for quantities
     * @access private
     */
    private $unit = null;

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        foreach( array_keys( $this->budget ) as $ix ) {
            $inside = [];
            if( ! $this->budget[$ix]->isValid( $inside )) {
                $local[self::BUDGET][$ix] = $inside;
            }
        }
        if( empty( $this->id )) {
            $local[self::ID] = false;
        }
        if( empty( $this->name )) {
            $local[self::NAME] = false;
        }
        if( empty( $this->type )) {
            $local[self::TYPE] = false;
        }
        if( ! empty( $local )) {
            $expected[self::ACCOUNT] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param BudgetType $budget
     * @return static
     */
    public function addBudget( BudgetType $budget ) {
        $this->budget[] = $budget;
        return $this;
    }

    /**
     * @return array
     */
    public function getBudget() {
        return $this->budget;
    }

    /**
     * @param array  [ *BudgetType ]
     * @return static
     * @throws InvalidArgumentException
     */
    public function setBudget( array $budget ) {
        foreach( $budget as $ix => $value) {
            if( $value instanceof BudgetType ) {
                $this->budget[$ix] = $value;
            }
            else {
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::BUDGET, $ix, self::BUDGET ));
            }
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
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

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     * @return static
     * @throws InvalidArgumentException
     */
    public function setType( $type ) {
        $this->type = CommonFactory::assertInEnumeration( $type, self::$typeEnumeration );
        return $this;
    }

    /**
     * @return string
     */
    public function getUnit() {
        return $this->unit;
    }

    /**
     * @param string $unit
     * @return static
     * @throws InvalidArgumentException
     */
    public function setUnit( $unit ) {
        $this->unit = CommonFactory::assertString( $unit );
        return $this;
    }

}