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
namespace Kigkonsult\Sie5Sdk\XMLWrite;

use Kigkonsult\Sie5Sdk\Dto\CustomerInvoiceType;

use function is_null;

class CustomerInvoiceTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{

    /**
     * Write
     * @param CustomerInvoiceType $customerInvoiceType
     *
     */
    public function write( CustomerInvoiceType $customerInvoiceType ) {
        $XMLattributes = $customerInvoiceType->getXMLattributes();;
        parent::SetWriterStartElement( $this->writer, self::CUSTOMERINVOICE, $XMLattributes );

        parent::writeAttribute( $this->writer, self::ID,   $customerInvoiceType->getId());
        parent::writeAttribute( $this->writer, self::NAME, $customerInvoiceType->getName());
        parent::writeAttribute( $this->writer, self::CUSTOMERID, $customerInvoiceType->getCustomerId());
        parent::writeAttribute( $this->writer, self::INVOICENUMBER, $customerInvoiceType->getInvoiceNumber());
        parent::writeAttribute( $this->writer, self::OCRNUMBER, $customerInvoiceType->getOcrNumber());
        $dueDate = $customerInvoiceType->getDueDate();
        if( ! is_null( $dueDate )) {
            self::writeAttribute( $this->writer, self::DUEDATE, $dueDate->format( self::FMTDATE ));
        }
        foreach( $customerInvoiceType->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE == $key ) {
                $key = self::XSITYPE;
            }
            parent::writeAttribute( $this->writer, $key, $value );
        }

        $writer = new BalancesTypeWriter( $this->writer );
        foreach( $customerInvoiceType->getBalances() as $element ) {
            $writer->write( $element );
        }

        $originalAmount = $customerInvoiceType->getOriginalAmount();
        if( ! is_null( $originalAmount )) {
            OriginalAmountTypeWriter::factory( $this->writer )->write( $originalAmount );
        }

        $this->writer->endElement();
    }
}

