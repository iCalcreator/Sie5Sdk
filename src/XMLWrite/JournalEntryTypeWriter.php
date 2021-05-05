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
 * @version   1.0
 * @license   Subject matter of licence is the software Sie5Sdk.
 *            The above copyright, link, package and version notices,
 *            this licence notice shall be included in all copies or substantial
 *            portions of the Sie5Sdk.
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

use Kigkonsult\Sie5Sdk\Dto\JournalEntryType;

use function is_array;

class JournalEntryTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param JournalEntryType $journalEntryType
     *
     */
    public function write( JournalEntryType $journalEntryType )
    {
        $XMLattributes = $journalEntryType->getXMLattributes();
        parent::setWriterStartElement( $this->writer, self::JOURNALENTRY, $XMLattributes );

        parent::writeAttribute( $this->writer, self::ID, (string) $journalEntryType->getId());
        $journalDate = $journalEntryType->getJournalDate();
        if( ! empty( $journalDate )) {
            self::writeAttribute(
                $this->writer,
                self::JOURNALDATE,
                $journalDate->format( self::FMTDATE )
            );
        }
        $var = $journalEntryType->getText();
        if( ! empty( $var )) {
            parent::writeAttribute( $this->writer, self::TEXT, $var );
        }
        $var = $journalEntryType->getReferenceId();
        if( ! empty( $var )) {
            parent::writeAttribute( $this->writer, self::REFERENCEID, $var );
        }
        foreach( $journalEntryType->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE == $key ) {
                $key = self::XSITYPE;
            }
            parent::writeAttribute( $this->writer, $key, $value );
        }

        $entryInfo = $journalEntryType->getEntryInfo();
        if( ! empty( $entryInfo )) {
            EntryInfoTypeWriter::factory( $this->writer )->write( $entryInfo );
        }
        $originalEntryInfo = $journalEntryType->getOriginalEntryInfo();
        if( ! empty( $originalEntryInfo )) {
            OriginalEntryInfoTypeWriter::factory( $this->writer )->write( $originalEntryInfo );
        }
        $var = $journalEntryType->getLedgerEntry();
        if( is_array( $var ) && ! empty( $var )) {
            $writer = new LedgerEntryTypeWriter( $this->writer );
            foreach( $var as $element ) {
                $writer->write( $element );
            }
        }
        $lockingInfo = $journalEntryType->getLockingInfo();
        if( ! empty( $lockingInfo )) {
            LockingInfoTypeWriter::factory( $this->writer )->write( $lockingInfo );
        }
        $var = $journalEntryType->getVoucherReference();
        if( is_array( $var ) && ! empty( $var )) {
            $writer = new VoucherReferenceTypeWriter( $this->writer );
            foreach( $var as $element ) {
                $writer->write( $element );
            }
        } // end if

        $var = $journalEntryType->getCorrectedBy();
        if( is_array( $var ) && ! empty( $var )) {
            $writer = new CorrectedByTypeWriter( $this->writer );
            foreach( $var as $element ) {
                $writer->write( $element );
            }
        }

        $this->writer->endElement();
    }
}
