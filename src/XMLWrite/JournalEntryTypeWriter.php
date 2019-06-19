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

use Kigkonsult\Sie5Sdk\Dto\JournalEntryType;

use function is_null;

class JournalEntryTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{

    /**
     * Write
     * @param JournalEntryType $journalEntryType
     *
     */
    public function write( JournalEntryType $journalEntryType ) {
        $XMLattributes = $journalEntryType->getXMLattributes();;
        parent::SetWriterStartElement( $this->writer, self::JOURNALENTRY, $XMLattributes );

        parent::writeAttribute( $this->writer, self::ID, $journalEntryType->getId());
        $journalDate = $journalEntryType->getJournalDate();
        if( ! is_null( $journalDate )) {
            self::writeAttribute(
                $this->writer,
                self::JOURNALDATE,
                $journalDate->format( self::FMTDATE )
            );
        }
        parent::writeAttribute( $this->writer, self::TEXT,        $journalEntryType->getText());
        parent::writeAttribute( $this->writer, self::REFERENCEID, $journalEntryType->getReferenceId());
        foreach( $journalEntryType->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE == $key ) {
                $key = self::XSITYPE;
            }
            parent::writeAttribute( $this->writer, $key, $value );
        }

        $entryInfo = $journalEntryType->getEntryInfo();
        if( ! is_null( $entryInfo )) {
            EntryInfoTypeWriter::factory( $this->writer )->write( $entryInfo );
        }
        $originalEntryInfo = $journalEntryType->getOriginalEntryInfo();
        if( ! is_null( $originalEntryInfo )) {
            OriginalEntryInfoTypeWriter::factory( $this->writer )->write( $originalEntryInfo );
        }
        $writer = new LedgerEntryTypeWriter( $this->writer );
        foreach( $journalEntryType->getLedgerEntry() as $element ) {
            $writer->write( $element );
        }
        $lockingInfo = $journalEntryType->getLockingInfo();
        if( ! is_null( $lockingInfo )) {
            LockingInfoTypeWriter::factory( $this->writer )->write( $lockingInfo );
        }
        $writer = new VoucherReferenceTypeWriter( $this->writer );
        foreach( $journalEntryType->getVoucherReference() as $element ) {
            $writer->write( $element );
        }
        $writer = new CorrectedByTypeWriter( $this->writer );
        foreach( $journalEntryType->getCorrectedBy() as $element ) {
            $writer->write( $element );
        }

        $this->writer->endElement();
    }
}
