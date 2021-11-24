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

use Kigkonsult\Sie5Sdk\Dto\JournalEntryTypeEntry;

use function is_array;

class JournalEntryTypeEntryWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param JournalEntryTypeEntry $journalEntryTypeEntry
     *
     */
    public function write( JournalEntryTypeEntry $journalEntryTypeEntry ) : void
    {
        $XMLattributes = $journalEntryTypeEntry->getXMLattributes();
        self::setWriterStartElement( $this->writer, self::JOURNALENTRY, $XMLattributes );

        $var = $journalEntryTypeEntry->getId();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::ID, (string) $var );
        }
        self::writeAttribute(
            $this->writer,
            self::JOURNALDATE,
            $journalEntryTypeEntry->getJournalDate()->format( self::FMTDATE )
        );
        $var = $journalEntryTypeEntry->getText();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::TEXT, $var );
        }
        $var = $journalEntryTypeEntry->getReferenceId();
        if( ! empty( $var )) {
            self::writeAttribute( $this->writer, self::REFERENCEID, $var );
        }
        foreach( $journalEntryTypeEntry->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE === $key ) {
                $key = self::XSITYPE;
            }
            self::writeAttribute( $this->writer, (string) $key, $value );
        }

        $originalEntryInfo = $journalEntryTypeEntry->getOriginalEntryInfo();
        if( ! empty( $originalEntryInfo )) {
            OriginalEntryInfoTypeWriter::factory( $this->writer )->write( $originalEntryInfo );
        }

        $ledgerEntries = $journalEntryTypeEntry->getLedgerEntry();
        if( is_array( $ledgerEntries ) && ! empty( $ledgerEntries )) {
            $writer = new LedgerEntryTypeEntryWriter( $this->writer );
            foreach( $ledgerEntries as $element ) {
                $writer->write( $element );
            }
        } // end if

        $var    = $journalEntryTypeEntry->getVoucherReference();
        if( ! empty( $var )) {
            $writer = new VoucherReferenceTypeWriter( $this->writer );
            foreach( $var as $element ) {
                $writer->write( $element );
            }
        }

        $this->writer->endElement();
    }
}
