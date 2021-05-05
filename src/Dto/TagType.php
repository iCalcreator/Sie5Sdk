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

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

class TagType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var string[]
     *
     * Attribute name="accountId" use="required" type="sie:AccountNumber"
     * <xsd:restriction base="xsd:string">
     *   <xsd:pattern value="[0-9]+"/>
     * </xsd:restriction>
     */
    private $accountRef = [];

    /**
     * @var string
     *
     * Attribute name="name" type="xsd:string" use="required"
     */
    private $name = null;

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) : bool
    {
        $local = [];
        if( empty( $this->accountRef )) {
            $local[self::ACCOUNTREF] = false;
        }
        if( empty( $this->name )) {
            $local[self::NAME] = false;
        }
        if( ! empty( $local )) {
            $expected[self::TAG] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param string $accountNumber
     * @return static
     * @throws InvalidArgumentException
     */
    public function addAccountRef( string $accountNumber ) : self
    {
        $this->accountRef[] = CommonFactory::assertAccountNumber( $accountNumber );
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAccountRef() : array
    {
        return $this->accountRef;
    }

    /**
     * @param array $accountRef string[]
     * @return TagType
     * @throws InvalidArgumentException
     */
    public function setAccountRef( array $accountRef ) : self
    {
        foreach( $accountRef as $ix => $accountNumber ) {
            $this->accountRef[] = CommonFactory::assertAccountNumber( $accountNumber, $ix );
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TagType
     */
    public function setName( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }
}
