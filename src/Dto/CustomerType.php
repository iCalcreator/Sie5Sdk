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

class CustomerType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var string
     *
     * Attribute name="id"             type="xsd:string" use="required"
     */
    private $id = null;

    /**
     * @var string
     *
     * Attribute name="name"           type="xsd:string" use="required"
     */
    private $name = null;

    /**
     * @var string
     *
     * Attribute name="organizationId" type="xsd:string"
     */
    private $organizationId = null;

    /**
     * @var string
     *
     * Attribute name="vatNr"          type="xsd:string"
     */
    private $vatNr = null;

    /**
     * @var string
     *
     * Attribute name="address1"       type="xsd:string"
     */
    private $address1 = null;

    /**
     * @var string
     *
     * Attribute name="address2"       type="xsd:string"
     */
    private $address2 = null;

    /**
     * @var string
     *
     * Attribute name="zipcode"        type="xsd:string"
     */
    private $zipcode = null;

    /**
     * @var string
     *
     * Attribute name="city"           type="xsd:string"
     */
    private $city = null;

    /**
     * @var string
     *
     * Attribute name="country"        type="xsd:string"
     */
    private $country = null;

    use ExtensionAttributeTrait;

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) : bool
    {
        $local = [];
        if( null == $this->id ) {
            $local[self::ID] = false;
        }
        if( empty( $this->name )) {
            $local[self::NAME] = false;
        }
        if( ! empty( $local )) {
            $expected[self::CUSTOMER] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return CustomerType
     */
    public function setId( string $id ) : self
    {
        $this->id = $id;
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
     * @return CustomerType
     */
    public function setName( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @param string $organizationId
     * @return CustomerType
     */
    public function setOrganizationId( string $organizationId ) : self
    {
        $this->organizationId = $organizationId;
        return $this;
    }

    /**
     * @return string
     */
    public function getVatNr()
    {
        return $this->vatNr;
    }

    /**
     * @param string $vatNr
     * @return CustomerType
     */
    public function setVatNr( string $vatNr ) : self
    {
        $this->vatNr = $vatNr;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     * @return CustomerType
     */
    public function setAddress1( string $address1 ) : self
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address2
     * @return CustomerType
     */
    public function setAddress2( string $address2 ) : self
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     * @return CustomerType
     */
    public function setZipcode( string $zipcode ) : self
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return CustomerType
     */
    public function setCity( string $city ) : self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return CustomerType
     */
    public function setCountry( string $country ) : self
    {
        $this->country = $country;
        return $this;
    }
}
