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
namespace Kigkonsult\Sie5Sdk\XMLWrite;

use Kigkonsult\Sie5Sdk\Dto\SupplierType;

class SupplierTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param SupplierType $supplierType
     *
     */
    public function write( SupplierType $supplierType ) : void
    {
        $XMLattributes = $supplierType->getXMLattributes();
        self::setWriterStartElement( $this->writer, self::SUPPLIER, $XMLattributes );

        self::writeAttribute( $this->writer, self::ID, $supplierType->getId() );
        self::writeAttribute( $this->writer, self::NAME, $supplierType->getName() );
        $var = $supplierType->getOrganizationId();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::ORGANIZATIONID, $supplierType->getOrganizationId() );
        }
        $var = $supplierType->getVatNr();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::VATNR, $var );
        }
        $var = $supplierType->getAddress1();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::ADDRESS1, $var );
        }
        $var = $supplierType->getZipcode();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::ZIPCODE, $var );
        }
        $var = $supplierType->getAddress2();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::ADDRESS2, $var );
        }
        $var = $supplierType->getCity();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::CITY, $var );
        }
        $var = $supplierType->getCountry();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::COUNTRY, $var );
        }
        $var = $supplierType->getBgAccount();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::BGACCOUNT, $var );
        }
        $var = $supplierType->getPgAccount();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::PGACCOUNT, $var );
        }
        $var = $supplierType->getBic();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::BIC, $var );
        }
        $var = $supplierType->getIban();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::IBAN, $var );
        }
        foreach( $supplierType->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE === $key ) {
                $key = self::XSITYPE;
            }
            self::writeAttribute( $this->writer, (string) $key, $value );
        } // end foreach

        $this->writer->endElement();
    }
}
