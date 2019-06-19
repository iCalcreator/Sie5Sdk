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

use Kigkonsult\Sie5Sdk\Dto\JournalEntryTypeEntry;

use function is_null;

class JournalEntryTypeEntryWriter extends Sie5WriterBase implements Sie5WriterInterface
{

    /**
     * Write
     * @param JournalEntryTypeEntry $journalEntryTypeEntry
     *
     */
    public function write( JournalEntryTypeEntry $journalEntryTypeEntry ) {
        $XMLattributes = $journalEntryTypeEntry->getXMLattributes();;
        parent::SetWriterStartElement( $this->writer, self::JOURNALENTRY, $XMLattributes );

        parent::writeAttribute( $this->writer, self::ID, $journalEntryTypeEntry->getId());
        $journalDate = $journalEntryTypeEntry->getJournalDate();
        if( ! is_null( $journalDate )) {
            self::writeAttribute(
                $this->writer,self::JOURNALDATE, $journalDate->format( self::FMTDATE
                )
            );
        }
        parent::writeAttribute( $this->writer, self::TEXT,        $journalEntryTypeEntry->getText());
        parent::writeAttribute( $this->writer, self::REFERENCEID, $journalEntryTypeEntry->getReferenceId());
        foreach( $journalEntryTypeEntry->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE == $key ) {
                $key = self::XSITYPE;
            }
            parent::writeAttribute( $this->writer, $key, $value );
        }

        $originalEntryInfo = $journalEntryTypeEntry->getOriginalEntryInfo();
        if( ! is_null( $originalEntryInfo )) {
            OriginalEntryInfoTypeWriter::factory( $this->writer )->write( $originalEntryInfo );
        }
        $writer = new LedgerEntryTypeEntryWriter( $this->writer );
        foreach( $journalEntryTypeEntry->getLedgerEntry() as $element ) {
            $writer->write( $element );
        }
        $writer = new VoucherReferenceTypeWriter( $this->writer );
        foreach( $journalEntryTypeEntry->getVoucherReference() as $element ) {
            $writer->write( $element );
        }

        $this->writer->endElement();
    }
}
