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

use Kigkonsult\Sie5Sdk\Dto\SupplierInvoicesType;

use function is_array;

class SupplierInvoicesTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param SupplierInvoicesType $supplierInvoicesType
     *
     */
    public function write( SupplierInvoicesType $supplierInvoicesType )
    {
        $XMLattributes = $supplierInvoicesType->getXMLattributes();
        parent::setWriterStartElement( $this->writer, self::SUPPLIERINVOICES, $XMLattributes );

        parent::writeAttribute(
            $this->writer, self::PRIMARYACCOUNTID, $supplierInvoicesType->getPrimaryAccountId()
        );
        $var = $supplierInvoicesType->getName();
        if( ! empty( $var )) {
            parent::writeAttribute( $this->writer, self::NAME, $var );
        }

        $var = $supplierInvoicesType->getSecondaryAccountRef();
        if( is_array( $var ) && ! empty( $var )) {
            foreach( $var as $accountId ) {
                parent::setWriterStartElement(
                    $this->writer, self::SECONDARYACCOUNTREF, $supplierInvoicesType->getXMLattributes()
                );
                parent::writeAttribute( $this->writer, self::ACCOUNTID, $accountId );
                $this->writer->endElement();
            } // end foreach
        } // end if

        $invoices = $supplierInvoicesType->getSupplierInvoice();
        if( is_array( $invoices ) && ! empty( $invoices )) {
            $writer = new SupplierInvoiceTypeWriter( $this->writer );
            foreach( $invoices as $element ) {
                $writer->write( $element );
            }
        } // end if

        $this->writer->endElement();
    }
}
