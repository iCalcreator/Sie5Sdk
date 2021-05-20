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
 * @version   1.0
 * @license   Subject matter of licence is the software Sie5Sdk.
 *            The above copyright, link, package and version notices,
 *            this licence notice shall be included in all copies or substantial
 *            portions of the Sie5Sdk.
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

use DateTime;
use InvalidArgumentException;
use TypeError;

abstract class SubdividedAccountObjectType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var BalancesType[]
     *
     * Attribute minOccurs="0" maxOccurs="unbounded"
     */
    protected $balances = [];

    /**
     * @var OriginalAmountType
     *
     * maxOccurs="1"  minOccurs="1"
     */
    protected $originalAmount = null;

    /**
     * @var string
     *
     * Attribute name="id" type="xsd:string" use="required"
     * Item identifier
     */
    protected $id = null;

    /**
     * @var string
     *
     * Attribute name="name" type="xsd:string" use="optional"
     * Item name
     */
    protected $name = null;

    use ExtensionAttributeTrait;

    /**
     * Factory method, set id and OriginalAmountType (date, amount)
     *
     * @param string   $id
     * @param DateTime $date
     * @param mixed    $amount
     * @return static
     * @throws InvalidArgumentException
     */
    public static function factoryIdDateAmount( string $id, DateTime $date, $amount ) : self
    {
        return self::factory()
            ->setId( $id )
            ->setOriginalAmount( OriginalAmountType::factoryDateAmount( $date, $amount ));
    }

    /**
     * Add single BalancesType
     *
     * @param BalancesType $balance
     * @return static
     */
    public function addBalance( BalancesType $balance ) : self
    {
        $this->balances[] = $balance;
        return $this;
    }

    /**
     * @return BalancesType[]
     */
    public function getBalances() : array
    {
        return $this->balances;
    }

    /**
     * Set BalancesTypes, array
     *
     * @param BalancesType[] $balances
     * @return static
     * @throws TypeError
     */
    public function setBalances( array $balances ) : self
    {
        foreach( $balances as $value ) {
            $this->addBalance( $value );
        } // end foreach
        return $this;
    }

    /**
     * @return null|OriginalAmountType
     */
    public function getOriginalAmount()
    {
        return $this->originalAmount;
    }

    /**
     * @param OriginalAmountType $originalAmount
     * @return static
     */
    public function setOriginalAmount( OriginalAmountType $originalAmount ) : self
    {
        $this->originalAmount = $originalAmount;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getId()
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
        $this->id = $id;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }
}
