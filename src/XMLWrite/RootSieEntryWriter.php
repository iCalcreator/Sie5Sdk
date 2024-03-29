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

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Dto\SieEntry;
use Kigkonsult\DsigSdk\XMLWrite\SignatureTypeWriter;

use function is_array;

class RootSieEntryWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param SieEntry $sieEntry
     * @throws InvalidArgumentException
     */
    public function write( SieEntry $sieEntry ) : void
    {
        $XMLattributes = $sieEntry->getXMLattributes();
        self::setWriterStartElement( $this->writer, self::SIEENTRY, $XMLattributes );

        $fileInfo = $sieEntry->getFileInfo();
        if( ! empty( $fileInfo )) {
            FileInfoTypeEntryWriter::factory( $this->writer)->write( $fileInfo );
        }

        $accounts = $sieEntry->getAccounts();
        if( ! empty( $accounts )) {
            AccountsTypeEntryWriter::factory( $this->writer)->write( $accounts );
        }

        $dimensions = $sieEntry->getDimensions();
        if( ! empty( $dimensions )) {
            DimensionsTypeEntryWriter::factory( $this->writer)->write( $dimensions );
        }

        $invoices =$sieEntry->getCustomerInvoices();
        if( is_array( $invoices ) && ! empty( $invoices )) {
            $writer = new CustomerInvoicesTypeEntryWriter( $this->writer );
            foreach( $invoices as $element ) {
                $writer->write( $element );
            }
        } // end if

        $invoices = $sieEntry->getSupplierInvoices();
        if( is_array( $invoices ) && ! empty( $invoices )) {
            $writer = new SupplierInvoicesTypeEntryWriter( $this->writer );
            foreach( $invoices as $element ) {
                $writer->write( $element );
            }
        } // end if

        $fixedAssets = $sieEntry->getFixedAssets();
        if( is_array( $fixedAssets ) && ! empty( $fixedAssets )) {
            $writer = new FixedAssetsTypeEntryWriter( $this->writer );
            foreach( $fixedAssets as $element ) {
                $writer->write( $element );
            }
        } // end if

        $sccounts = $sieEntry->getGeneralSubdividedAccount();
        if( is_array( $sccounts ) && ! empty( $sccounts )) {
            $writer = new GeneralSubdividedAccountTypeEntryWriter( $this->writer );
            foreach( $sccounts as $element ) {
                $writer->write( $element );
            }
        } // end if

        $customers = $sieEntry->getCustomers();
        if( ! empty( $customers )) {
            CustomersTypeWriter::factory( $this->writer)->write( $customers );
        }

        $suppliers = $sieEntry->getSuppliers();
        if( ! empty( $suppliers )) {
            SuppliersTypeWriter::factory( $this->writer)->write( $suppliers );
        }

        $journals = $sieEntry->getJournal();
        if( is_array( $journals ) && ! empty( $journals )) {
            $writer = new JournalTypeEntryWriter( $this->writer );
            foreach( $journals as $journal ) {
                $writer->write( $journal );
            }
        } // end if

        $documents = $sieEntry->getDocuments();
        if( ! empty( $documents )) {
            DocumentsTypeWriter::factory( $this->writer)->write( $documents );
        }

        $signature = $sieEntry->getSignature();
        if( ! empty( $signature )) {
            SignatureTypeWriter::factory( $this->writer)->write( $signature );
        }

        $this->writer->endElement();
    }
}
