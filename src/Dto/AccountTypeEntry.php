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

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;
use TypeError;

use function array_keys;

class AccountTypeEntry extends Sie5DtoExtAttrBase
{
    /**
     * @var BudgetType[]
     */
    private array $budget = [];

    /**
     * @var string|null
     *
     * Attribute name="id" type="sie:AccountNumber" use="required"
     * pattern value="[0-9]+"
     * Unique account identifier. The account number.
     */
    private ?string $id = null;

    /**
     * @var string|null
     *
     * Attribute name="name" type="xsd:string" use="required"
     * Account name
     */
    private ?string $name = null;

    /**
     * @var string|null
     *
     * Attribute name="type" use="required"
     * enumeration : "asset", "liability", "equity", "cost", "income", "statistics"
     */
    private ?string $type = null;

    /**
     * @var string[]
     */
    public static array $typeEnumeration = [
        self::ASSET, self::LIABILITY, self::EQUITY, self::COST, self::INCOME, self::STATISTICS
    ];

    /**
     * @var string|null
     *
     * Attribute name="unit" type="xsd:string"
     * Unit for quantities
     */
    private ?string $unit = null;

    /**
     * Factory method, set id, name and type
     *
     * @param string $id
     * @param string $name
     * @param string $type
     * @return static
     */
    public static function factoryIdNameType( string $id, string $name, string $type ) : self
    {
        return self::factory()
                   ->setId( $id )
                   ->setName( $name )
                   ->setType( $type );
    }

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
        foreach( array_keys( $this->budget ) as $ix ) {
            $inside[$ix] = [];
            if( $this->budget[$ix]->isValid( $inside[$ix] )) {
                unset( $inside[$ix] );
            }
        } // end foreach
        if( ! empty( $inside )) {
            $key         = self::getClassPropStr( self::class, self::ACCOUNT );
            $local[$key] = $inside;
        } // end if
        if( null ===$this->id ) {
            $local[] = self::errMissing(self::class, self::ID );
        }
        if( null ===$this->name ) {
            $local[] = self::errMissing(self::class, self::NAME );
        }
        if( null ===$this->type ) {
            $local[] = self::errMissing(self::class, self::TYPE );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single BudgetType
     *
     * @param BudgetType $budget
     * @return static
     */
    public function addBudget( BudgetType $budget )  : self
    {
        $this->budget[] = $budget;
        return $this;
    }

    /**
     * @return array
     */
    public function getBudget() : array
    {
        return $this->budget;
    }

    /**
     * Set BudgetTypes, array
     *
     * @param BudgetType[]  $budget
     * @return static
     * @throws TypeError
     */
    public function setBudget( array $budget ) : self
    {
        foreach( $budget as $value) {
            $this->addBudget( $value );
        }
        return $this;
    }

    /**
     * @return null|string
     */
    public function getId() : ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     * @throws InvalidArgumentException
     */
    public function setId( string $id ) : self
    {
        $this->id = CommonFactory::assertAccountNumber( $id );
        return $this;
    }

    /**
     * @return null|string
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     * @throws InvalidArgumentException
     */
    public function setName( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getType() : ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return static
     * @throws InvalidArgumentException
     */
    public function setType( string $type ) : self
    {
        $this->type = CommonFactory::assertInEnumeration( $type, self::$typeEnumeration );
        return $this;
    }

    /**
     * @return null|string
     */
    public function getUnit() : ?string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     * @return static
     * @throws InvalidArgumentException
     */
    public function setUnit( string $unit ) : self
    {
        $this->unit = $unit;
        return $this;
    }
}
