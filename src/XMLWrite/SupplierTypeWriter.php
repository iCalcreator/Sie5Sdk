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
    public function write( SupplierType $supplierType )
    {
        $XMLattributes = $supplierType->getXMLattributes();
        parent::setWriterStartElement( $this->writer, self::SUPPLIER, $XMLattributes );

        parent::writeAttribute( $this->writer, self::ID, $supplierType->getId() );
        parent::writeAttribute( $this->writer, self::NAME, $supplierType->getName() );
        $var = $supplierType->getOrganizationId();
        if( ! empty( $var )) {
            parent::writeAttribute( $this->writer, self::ORGANIZATIONID, $supplierType->getOrganizationId() );
        }
        $var = $supplierType->getVatNr();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::VATNR, $var );
        }
        $var = $supplierType->getAddress1();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::ADDRESS1, $var );
        }
        $var = $supplierType->getZipcode();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::ZIPCODE, $var );
        }
        $var = $supplierType->getAddress2();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::ADDRESS2, $var );
        }
        $var = $supplierType->getCity();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::CITY, $var );
        }
        $var = $supplierType->getCountry();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::COUNTRY, $var );
        }
        $var = $supplierType->getBgAccount();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::BGACCOUNT, $var );
        }
        $var = $supplierType->getPgAccount();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::PGACCOUNT, $var );
        }
        $var = $supplierType->getBic();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::BIC, $var );
        }
        $var = $supplierType->getIban();
        if( ! empty( $var ) ) {
            parent::writeAttribute( $this->writer, self::IBAN, $var );
        }
        foreach( $supplierType->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE == $key ) {
                $key = self::XSITYPE;
            }
            parent::writeAttribute( $this->writer, $key, $value );
        } // end foreach

        $this->writer->endElement();
    }
}
