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
use Kigkonsult\Sie5Sdk\Dto\Sie;
use Kigkonsult\DsigSdk\XMLWrite\SignatureTypeWriter;

use function is_null;

class RootSieWriter extends Sie5WriterBase implements Sie5WriterInterface
{

    /**
     * Write
     *
     * @param Sie $sie
     * @throws InvalidArgumentException
     */
    public function write( Sie $sie ) {
        $XMLattributes = $sie->getXMLattributes();;
        parent::SetWriterStartElement( $this->writer, self::SIE, $XMLattributes );

        $fileInfo = $sie->getFileInfo();
        if( ! is_null( $fileInfo )) {
            FileInfoTypeWriter::factory( $this->writer )->write( $fileInfo );
        }

        $accounts = $sie->getAccounts();
        if( ! is_null( $accounts )) {
            AccountsTypeWriter::factory( $this->writer )->write( $accounts );
        }

        $dimensions = $sie->getDimensions();
        if( ! is_null( $dimensions )) {
            DimensionsTypeWriter::factory( $this->writer )->write( $dimensions );
        }

        $writer = new CustomerInvoicesTypeWriter( $this->writer );
        foreach( $sie->getCustomerInvoices() as $element ) {
            $writer->write( $element );
        }

        $writer = new SupplierInvoicesTypeWriter( $this->writer );
        foreach( $sie->getSupplierInvoices() as $element ) {
            $writer->write( $element );
        }

        $writer = new FixedAssetsTypeWriter( $this->writer );
        foreach( $sie->getFixedAssets() as $element ) {
            $writer->write( $element );
        }

        $writer = new GeneralSubdividedAccountTypeWriter( $this->writer );
        foreach( $sie->getGeneralSubdividedAccount() as $element ) {
            $writer->write( $element );
        }

        $customers = $sie->getCustomers();
        if( ! is_null( $customers )) {
            CustomersTypeWriter::factory( $this->writer )->write( $customers );
        }

        $suppliers = $sie->getSuppliers();
        if( ! is_null( $suppliers )) {
            SuppliersTypeWriter::factory( $this->writer )->write( $suppliers );
        }

        $accountAggregations = $sie->getAccountAggregations();
        if( ! is_null( $accountAggregations )) {
            AccountAggregationsTypeWriter::factory( $this->writer )->write( $accountAggregations );
        }

        $writer = new JournalTypeWriter( $this->writer );
        foreach( $sie->getJournal() as $journal ) {
            $writer->write( $journal );
        }

        $documents = $sie->getDocuments();
        if( ! is_null( $documents )) {
            DocumentsTypeWriter::factory( $this->writer )->write( $documents );
        }

        $signature = $sie->getSignature();
        if( ! is_null( $signature )) {
            SignatureTypeWriter::factory( $this->writer )->write( $signature );
        }

        $this->writer->endElement();
    }

}
