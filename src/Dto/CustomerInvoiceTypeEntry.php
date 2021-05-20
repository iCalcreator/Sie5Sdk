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

/**
 * Class CustomerInvoiceTypeEntry
 *
 * @package Kigkonsult\Sie5Sdk\Dto
 *
 * SubdividedAccountObjectTypeEntry::id kan anv. som CustomerInvoiceTypeEntry::invoiceNumber ?!
 */
class CustomerInvoiceTypeEntry extends SubdividedAccountObjectTypeEntry
{
    use ExtensionAttributeTrait;

    /**
     * @var string
     *
     * Attribute name="customerId"    type="xsd:string" use="required"
     */
    private $customerId = null;

    /**
     * @var string
     *
     * Attribute name="invoiceNumber" type="xsd:string" use="required"
     */
    private $invoiceNumber = null;

    /**
     * @var string
     *
     * Attribute name="ocrNumber"     type="xsd:string"
     */
    private $ocrNumber = null;

    /**
     * @var DateTime
     *
     * Attribute name="dueDate"       type="xsd:date"
     */
    private $dueDate = null;

    /**
     * Return bool true is instance is valid
     *
     * @param array $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null ) : bool
    {
        $local = [];
        // but both are required...
        if(( null === $this->id ) && ( null === $this->invoiceNumber )) {
            $local[] = self::errMissing(self::class, self::ID );
            $local[] = self::errMissing(self::class, self::INVOICENUMBER );
        }
        if( empty( $this->customerId )) {
            $local[self::CUSTOMERID] = false;
            $local[] = self::errMissing(self::class, self::CUSTOMERID );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return null|string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param string $customerId
     * @return static
     */
    public function setCustomerId( string $customerId ) : self
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber
     * @return static
     */
    public function setInvoiceNumber( string $invoiceNumber ) : self
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getOcrNumber()
    {
        return $this->ocrNumber;
    }

    /**
     * @param string $ocrNumber
     * @return static
     */
    public function setOcrNumber( string $ocrNumber ) : self
    {
        $this->ocrNumber = $ocrNumber;
        return $this;
    }

    /**
     * @return null|DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param DateTime $dueDate
     * @return static
     */
    public function setDueDate( DateTime $dueDate ) : self
    {
        $this->dueDate = $dueDate;
        return $this;
    }
}
