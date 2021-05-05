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
namespace Kigkonsult\Sie5Sdk\XMLParse;

use DateTime;
use Exception;
use Kigkonsult\Sie5Sdk\Dto\JournalEntryType;
use XMLReader;

use function count;
use function implode;
use function sprintf;

class JournalEntryTypeParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return JournalEntryType
     * @throws Exception
     */
    public function parse() : JournalEntryType
    {
        $journalEntryType = JournalEntryType::factory()->setXMLattributes( $this->reader );
        $this->logger->debug(
            sprintf( self::$FMTstartNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
        );
        if( $this->reader->hasAttributes ) {
            $extensionAttributes = [];
            while( $this->reader->moveToNextAttribute()) {
                $this->logger->debug(
                    sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                );
                switch( $this->reader->name ) {
                    case self::ID :
                        $journalEntryType->setId( $this->reader->value );
                        break;
                    case self::JOURNALDATE :
                        try {
                            $journalEntryType->setJournalDate( new DateTime( $this->reader->value ));
                        }
                        catch( Exception $e ) {
                            $this->logger->error(
                                sprintf( parent::$FMTERRDATE, $this->reader->value )
                            );
                            throw $e;
                        }
                        break;
                    case self::TEXT :
                        $journalEntryType->setText( $this->reader->value );
                        break;
                    case self::REFERENCEID :
                        $journalEntryType->setReferenceId( $this->reader->value );
                        break;
                    case self::XSITYPE :
                        $extensionAttributes[$this->reader->name] = $this->reader->value;
                        break;
                    default :
                        $extensionAttributes[$this->reader->name] = $this->reader->value;
                        break;
                } // end switch
            } // end while
            if( isset( $extensionAttributes[self::XSITYPE] ) && ( 2 <= count( $extensionAttributes ))) {
                $this->logger->debug(
                    sprintf( self::$FMTextAttrSaved, implode( self::$GLUE, array_keys( $extensionAttributes )))
                );
                $journalEntryType->setExtensionAttributes( $extensionAttributes );
            }
            $this->reader->moveToElement();
        } // end if
        if( $this->reader->isEmptyElement ) {
            return $journalEntryType;
        }
        $headElement = $this->reader->localName;
        $ledgerEntryTypeParser      = new LedgerEntryTypeParser( $this->reader );
        $voucherReferenceTypeParser = new VoucherReferenceTypeParser( $this->reader );
        $correctedByTypeParser      = new CorrectedByTypeParser( $this->reader );
        while( @$this->reader->read()) {
            if( XMLReader::SIGNIFICANT_WHITESPACE != $this->reader->nodeType ) {
                $this->logger->debug(
                    sprintf( self::$FMTreadNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
                );
            }
            switch( true ) {
                case ( XMLReader::END_ELEMENT == $this->reader->nodeType ) :
                    if( $headElement == $this->reader->localName ) {
                        break 2;
                    }
                    break;
                case ( XMLReader::ELEMENT != $this->reader->nodeType ) :
                    break;
                case ( self::ENTRYINFO == $this->reader->localName ) :
                    $journalEntryType->setEntryInfo(
                        EntryInfoTypeParser::factory( $this->reader )->parse()
                    );
                    break;
                case ( self::ORIGINALENTRYINFO == $this->reader->localName ) :
                    $journalEntryType->setOriginalEntryInfo(
                        OriginalEntryInfoTypeParser::factory( $this->reader )->parse()
                    );
                    break;
                case ( self::LEDGERENTRY == $this->reader->localName ) :
                    $journalEntryType->addLedgerEntry( $ledgerEntryTypeParser->parse());
                    break;
                case ( self::LOCKINGINFO == $this->reader->localName ) :
                    $journalEntryType->setLockingInfo( LockingInfoTypeParser::factory ($this->reader )->parse());
                    break;
                case (self::VOUCHERREFERENCE == $this->reader->localName ):
                    $journalEntryType->addVoucherReference( $voucherReferenceTypeParser->parse());
                    break;
                case (self::CORRECTEDBY == $this->reader->localName ):
                    $journalEntryType->addCorrectedBy( $correctedByTypeParser->parse());
                    break;
            } // end switch
        } // end while

        return $journalEntryType;
    }
}
