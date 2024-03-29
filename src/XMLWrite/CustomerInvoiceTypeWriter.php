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

use Kigkonsult\Sie5Sdk\Dto\CustomerInvoiceType;

use function is_array;

class CustomerInvoiceTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param CustomerInvoiceType $customerInvoiceType
     *
     */
    public function write( CustomerInvoiceType $customerInvoiceType ) : void
    {
        $XMLattributes = $customerInvoiceType->getXMLattributes();
        self::setWriterStartElement( $this->writer, self::CUSTOMERINVOICE, $XMLattributes );

        self::writeAttribute( $this->writer, self::ID, $customerInvoiceType->getId() );
        self::writeAttribute( $this->writer, self::NAME, $customerInvoiceType->getName() );
        self::writeAttribute( $this->writer, self::CUSTOMERID, $customerInvoiceType->getCustomerId() );
        self::writeAttribute( $this->writer, self::INVOICENUMBER, $customerInvoiceType->getInvoiceNumber() );
        $ocrNumber = $customerInvoiceType->getOcrNumber();
        if( ! empty( $ocrNumber )) {
            self::writeAttribute( $this->writer, self::OCRNUMBER, $ocrNumber );
        }
        $dueDate = $customerInvoiceType->getDueDate();
        if( ! empty( $dueDate )) {
            self::writeAttribute( $this->writer, self::DUEDATE, $dueDate->format( self::FMTDATE ));
        }
        foreach( $customerInvoiceType->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE === $key ) {
                $key = self::XSITYPE;
            }
            self::writeAttribute( $this->writer, (string) $key, $value );
        }

        $balances = $customerInvoiceType->getBalances();
        if( is_array( $balances ) && ! empty( $balances )) {
            $writer = new BalancesTypeWriter( $this->writer );
            foreach( $balances as $element ) {
                $writer->write( $element );
            }
        } // end if

        $originalAmount = $customerInvoiceType->getOriginalAmount();
        if( null !== $originalAmount ) {
            OriginalAmountTypeWriter::factory( $this->writer )->write( $originalAmount );
        }

        $this->writer->endElement();
    }
}
