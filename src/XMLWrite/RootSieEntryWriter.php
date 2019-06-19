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

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Dto\SieEntry;
use Kigkonsult\DsigSdk\XMLWrite\SignatureTypeWriter;

use function is_null;

class RootSieEntryWriter extends Sie5WriterBase implements Sie5WriterInterface
{

    /**
     * Writ
     *
     * @param SieEntry $sieEntry
     * @throws InvalidArgumentException
     */
    public function write( SieEntry $sieEntry ) {
        $XMLattributes = $sieEntry->getXMLattributes();;
        parent::SetWriterStartElement( $this->writer, self::SIEENTRY, $XMLattributes );

        $fileInfo = $sieEntry->getFileInfo();
        if( ! is_null( $fileInfo )) {
            FileInfoTypeEntryWriter::factory( $this->writer)->write( $fileInfo );
        }

        $accounts = $sieEntry->getAccounts();
        if( ! is_null( $accounts )) {
            AccountsTypeEntryWriter::factory( $this->writer)->write( $accounts );
        }

        $dimensions = $sieEntry->getDimensions();
        if( ! is_null( $dimensions )) {
            DimensionsTypeEntryWriter::factory( $this->writer)->write( $dimensions );
        }

        $writer = new CustomerInvoicesTypeEntryWriter( $this->writer );
        foreach( $sieEntry->getCustomerInvoices() as $element ) {
            $writer->write( $element );
        }

        $writer = new SupplierInvoicesTypeEntryWriter( $this->writer );
        foreach( $sieEntry->getSupplierInvoices() as $element ) {
            $writer->write( $element );
        }

        $writer = new FixedAssetsTypeEntryWriter( $this->writer );
        foreach( $sieEntry->getFixedAssets() as $element ) {
            $writer->write( $element );
        }

        $writer = new GeneralSubdividedAccountTypeEntryWriter( $this->writer );
        foreach(  $sieEntry->getGeneralSubdividedAccount() as $element ) {
            $writer->write( $element );
        }

        $customers = $sieEntry->getCustomers();
        if( ! is_null( $customers )) {
            CustomersTypeWriter::factory( $this->writer)->write( $customers );
        }

        $suppliers = $sieEntry->getSuppliers();
        if( ! is_null( $suppliers )) {
            SuppliersTypeWriter::factory( $this->writer)->write( $suppliers );
        }

        $writer = new JournalTypeEntryWriter( $this->writer );
        foreach( $sieEntry->getJournal() as $journal ) {
            $writer->write( $journal );
        }

        $documents = $sieEntry->getDocuments();
        if( ! is_null( $documents )) {
            DocumentsTypeWriter::factory( $this->writer)->write( $documents );
        }

        $signature = $sieEntry->getSignature();
        if( ! is_null( $signature )) {
            SignatureTypeWriter::factory( $this->writer)->write( $signature );
        }

        $this->writer->endElement();
    }

}