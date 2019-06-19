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

use DateTime;
use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

use function is_null;

class SupplierInvoiceTypeEntry extends SubdividedAccountObjectTypeEntry
{
    /**
     * @var string
     *            attribute name="SupplierId" type="xsd:string" use="required"
     * @access private
     */
    private $supplierId = null;

    /**
     * @var string
     *            attribute name="invoiceNumber" type="xsd:string" use="required"
     * @access private
     */
    private $invoiceNumber = null;

    /**
     * @var string
     *            attribute name="ocrNumber"     type="xsd:string"
     * @access private
     */
    private $ocrNumber = null;

    /**
     * @var DateTime
     *            attribute name="dueDate"       type="xsd:date"
     * @access private
     */
    private $dueDate = null;



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
        if( empty( $this->supplierId )) {
            $local[self::SUPPLIERID] = false;
        }
        if( empty( $this->invoiceNumber )) {
            $local[self::INVOICENUMBER] = false;
        }
        if( ! empty( $local )) {
            $expected[self::SUPPLIERINVOICE] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getSupplierId() {
        return $this->supplierId;
    }

    /**
     * @param string $supplierId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setSupplierId( $supplierId ) {
        $this->supplierId = CommonFactory::assertString( $supplierId );
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber() {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber
     * @return static
     * @throws InvalidArgumentException
     */
    public function setInvoiceNumber( $invoiceNumber ) {
        $this->invoiceNumber = CommonFactory::assertString( $invoiceNumber );
        return $this;
    }

    /**
     * @return string
     */
    public function getOcrNumber() {
        return $this->ocrNumber;
    }

    /**
     * @param string $ocrNumber
     * @return static
     * @throws InvalidArgumentException
     */
    public function setOcrNumber( $ocrNumber ) {
        $this->ocrNumber = CommonFactory::assertString( $ocrNumber );
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDueDate() {
        return $this->dueDate;
    }

    /**
     * @param DateTime $dueDate
     * @return static
     */
    public function setDueDate( DateTime $dueDate ) {
        $this->dueDate = $dueDate;
        return $this;
    }

}