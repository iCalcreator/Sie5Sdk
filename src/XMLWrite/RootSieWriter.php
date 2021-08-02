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

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Dto\Sie;
use Kigkonsult\DsigSdk\XMLWrite\SignatureTypeWriter;

use function is_array;

class RootSieWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param Sie $sie
     * @throws InvalidArgumentException
     */
    public function write( Sie $sie )
    {
        $XMLattributes = $sie->getXMLattributes();
        parent::setWriterStartElement( $this->writer, self::SIE, $XMLattributes );

        $fileInfo = $sie->getFileInfo();
        if( ! empty( $fileInfo )) {
            FileInfoTypeWriter::factory( $this->writer )->write( $fileInfo );
        }

        $accounts = $sie->getAccounts();
        if( ! empty( $accounts )) {
            AccountsTypeWriter::factory( $this->writer )->write( $accounts );
        }

        $dimensions = $sie->getDimensions();
        if( ! empty( $dimensions )) {
            DimensionsTypeWriter::factory( $this->writer )->write( $dimensions );
        }

        $invoices = $sie->getCustomerInvoices();
        if( is_array( $invoices ) && ! empty( $invoices )) {
            $writer = new CustomerInvoicesTypeWriter( $this->writer );
            foreach( $invoices as $element ) {
                $writer->write( $element );
            }
        } // end if

        $invoices = $sie->getSupplierInvoices();
        if( is_array( $invoices ) && ! empty( $invoices )) {
            $writer = new SupplierInvoicesTypeWriter( $this->writer );
            foreach( $invoices as $element ) {
                $writer->write( $element );
            }
        } // end if

        $fixedAssets = $sie->getFixedAssets();
        if( is_array( $fixedAssets ) && ! empty( $fixedAssets )) {
            $writer = new FixedAssetsTypeWriter( $this->writer );
            foreach( $fixedAssets as $element ) {
                $writer->write( $element );
            }
        } // end if

        $accounts = $sie->getGeneralSubdividedAccount();
        if( is_array( $accounts ) && ! empty( $accounts )) {
            $writer = new GeneralSubdividedAccountTypeWriter( $this->writer );
            foreach( $accounts as $element ) {
                $writer->write( $element );
            }
        } // end if

        $customers = $sie->getCustomers();
        if( ! empty( $customers )) {
            CustomersTypeWriter::factory( $this->writer )->write( $customers );
        }

        $suppliers = $sie->getSuppliers();
        if( ! empty( $suppliers )) {
            SuppliersTypeWriter::factory( $this->writer )->write( $suppliers );
        }

        $accountAggregations = $sie->getAccountAggregations();
        if( ! empty( $accountAggregations )) {
            AccountAggregationsTypeWriter::factory( $this->writer )->write( $accountAggregations );
        }

        $journals = $sie->getJournal();
        if( is_array( $journals ) && ! empty( $journals )) {
            $writer = new JournalTypeWriter( $this->writer );
            foreach( $journals as $journal ) {
                $writer->write( $journal );
            }
        } // end if

        $documents = $sie->getDocuments();
        if( ! empty( $documents )) {
            DocumentsTypeWriter::factory( $this->writer )->write( $documents );
        }

        $signature = $sie->getSignature();
        if( ! empty( $signature )) {
            SignatureTypeWriter::factory( $this->writer )->write( $signature );
        }

        $this->writer->endElement();
    }
}
