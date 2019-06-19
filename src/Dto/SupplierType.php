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

use function is_null;

class SupplierType extends Sie5DtoExtAttrBase
{

    /**
     * @var string
     *            attribute name="id"             type="xsd:string" use="required"
     * @access private
     */
    private $id = null;

    /**
     * @var string
     *            attribute name="name"           type="xsd:string" use="required"
     * @access private
     */
    private $name = null;

    /**
     * @var string
     *            attribute name="organizationId" type="xsd:string"
     * @access private
     */
    private $organizationId = null;

    /**
     * @var string
     *            attribute name="vatNr"          type="xsd:string"
     * @access private
     */
    private $vatNr = null;

    /**
     * @var string
     *            attribute name="address1"       type="xsd:string"
     * @access private
     */
    private $address1 = null;

    /**
     * @var string
     *            attribute name="address2"       type="xsd:string"
     * @access private
     */
    private $address2 = null;

    /**
     * @var string
     *            attribute name="zipcode"        type="xsd:string"
     * @access private
     */
    private $zipcode = null;

    /**
     * @var string
     *            attribute name="city"           type="xsd:string"
     * @access
     */
    private $city = null;

    /**
     * @var string
     *            attribute name="country"        type="xsd:string"
     * @access private
     */
    private $country = null;

    /**
     * @var string
     *            attribute name="BgAccount"      type="xsd:string"
     * @access private
     */
    private $bgAccount = null;

    /**
     * @var string
     *            attribute name="PgAccount"      type="xsd:string"
     * @access private
     */
    private $pgAccount = null;

    /**
     * @var string
     *            attribute name="BIC"            type="xsd:string"
     * @access private
     */
    private $bic = null;

    /**
     * @var string
     *            attribute name="IBAN"           type="xsd:string"
     * @access private
     */
    private $iban = null;

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( is_null( $this->id )) {
            $local[self::ID] = false;
        }
        if( empty( $this->name )) {
            $local[self::NAME] = false;
        }
        if( ! empty( $local )) {
            $expected[self::SUPPLIER] = $local;
            return false;
        }
        return true;
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

    /**
     * @return string
     */
    public function getOrganizationId() {
        return $this->organizationId;
    }

    /**
     * @param string $organizationId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setOrganizationId( $organizationId ) {
        $this->organizationId = CommonFactory::assertString( $organizationId );
        return $this;
    }

    /**
     * @return string
     */
    public function getVatNr() {
        return $this->vatNr;
    }

    /**
     * @param string $vatNr
     * @return static
     * @throws InvalidArgumentException
     */
    public function setVatNr( $vatNr ) {
        $this->vatNr = CommonFactory::assertString( $vatNr );
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1() {
        return $this->address1;
    }

    /**
     * @param string $address1
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAddress1( $address1 ) {
        $this->address1 = CommonFactory::assertString( $address1 );
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2() {
        return $this->address2;
    }

    /**
     * @param string $address2
     * @return static
     * @throws InvalidArgumentException
     */
    public function setAddress2( $address2 ) {
        $this->address2 = CommonFactory::assertString( $address2 );
        return $this;
    }

    /**
     * @return string
     */
    public function getZipcode() {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     * @return static
     * @throws InvalidArgumentException
     */
    public function setZipcode( $zipcode ) {
        $this->zipcode = CommonFactory::assertString( $zipcode );
        return $this;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $city
     * @return static
     * @throws InvalidArgumentException
     */
    public function setCity( $city ) {
        $this->city = CommonFactory::assertString( $city );
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param string $country
     * @return static
     * @throws InvalidArgumentException
     */
    public function setCountry( $country ) {
        $this->country = CommonFactory::assertString( $country );
        return $this;
    }

    /**
     * @return string
     */
    public function getBgAccount() {
        return $this->bgAccount;
    }

    /**
     * @param string $bgAccount
     * @return SupplierType
     * @throws InvalidArgumentException
     */
    public function setBgAccount( $bgAccount ) {
        $this->bgAccount = CommonFactory::assertString( $bgAccount );
        return $this;
    }

    /**
     * @return string
     */
    public function getPgAccount() {
        return $this->pgAccount;
    }

    /**
     * @param string $pgAccount
     * @return SupplierType
     * @throws InvalidArgumentException
     */
    public function setPgAccount( $pgAccount ) {
        $this->pgAccount = CommonFactory::assertString( $pgAccount );
        return $this;
    }

    /**
     * @return string
     */
    public function getBic() {
        return $this->bic;
    }

    /**
     * @param string $bic
     * @return SupplierType
     * @throws InvalidArgumentException
     */
    public function setBic( $bic ) {
        $this->bic = CommonFactory::assertString( $bic );
        return $this;
    }

    /**
     * @return string
     */
    public function getIban() {
        return $this->iban;
    }

    /**
     * @param string $iban
     * @return SupplierType
     * @throws InvalidArgumentException
     */
    public function setIban( $iban ) {
        $this->iban = CommonFactory::assertString( $iban );
        return $this;
    }

}