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

use Exception;
use Kigkonsult\DsigSdk\XMLParse\SignatureTypeParser;
use Kigkonsult\Sie5Sdk\Dto\SieEntry;
use XMLReader;

use function sprintf;

class RootSieEntryParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return SieEntry
     * @throws Exception
     */
    public function parse() : SieEntry
    {
        $sieEntry = SieEntry::factory()->setXMLattributes( $this->reader );
        $this->logger->debug(
            sprintf( self::$FMTstartNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
        );
        if( $this->reader->hasAttributes ) {
            while( $this->reader->moveToNextAttribute()) {
                $this->logger->debug(
                    sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                );
                $sieEntry->setXMLattribute( $this->reader->name, $this->reader->value );
            }
            $this->reader->moveToElement();
        } // end if
        if( $this->reader->isEmptyElement ) {
            return $sieEntry;
        }
        $headElement = $this->reader->localName;
        $customerInvoicesTypeEntryParser = new CustomerInvoicesTypeEntryParser( $this->reader );
        $supplierInvoicesTypeEntryParser = new SupplierInvoicesTypeEntryParser( $this->reader );
        $fixedAssetsTypeEntryParser      = new FixedAssetsTypeEntryParser( $this->reader );
        $generalSubdividedAccountTypeEntryParser = new GeneralSubdividedAccountTypeEntryParser( $this->reader );
        $journalTypeEntryParser          = new JournalTypeEntryParser( $this->reader );
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

                case ( self::FILEINFO == $this->reader->localName ) :  // occurs 1
                    $sieEntry->setFileInfo( FileInfoTypeEntryParser::factory( $this->reader )->parse());
                    break;

                case ( self::ACCOUNTS == $this->reader->localName ) :  // occurs 1
                    $sieEntry->setAccounts( AccountsTypeEntryParser::factory( $this->reader )->parse());
                    break;

                case ( self::DIMENSIONS == $this->reader->localName ) :  // occurs 0-1
                    $sieEntry->setDimensions( DimensionsTypeEntryParser::factory( $this->reader )->parse());
                    break;

                case ( self::CUSTOMERINVOICES == $this->reader->localName ) : // occurs 0-unbounded
                    $sieEntry->addCustomerInvoices( $customerInvoicesTypeEntryParser->parse());
                    break;

                case ( self::SUPPLIERINVOICES == $this->reader->localName ) : // occurs 0-unbounded
                    $sieEntry->addSupplierInvoices( $supplierInvoicesTypeEntryParser->parse());
                    break;
                case ( self::FIXEDASSETS == $this->reader->localName ) : // occurs 0-unbounded
                    $sieEntry->addFixedAsset( $fixedAssetsTypeEntryParser->parse());
                    break;

                case ( self::GENERALSUBDIVIDEDACCOUNT == $this->reader->localName ) : // occurs 0-unbounded
                    $sieEntry->addGeneralSubdividedAccount( $generalSubdividedAccountTypeEntryParser->parse());
                    break;

                case ( self::CUSTOMERS == $this->reader->localName ) :  // occurs 0-1
                    $sieEntry->setCustomers( CustomersTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::SUPPLIERS == $this->reader->localName ) :  // occurs 0-1
                    $sieEntry->setSuppliers( SuppliersTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::JOURNAL == $this->reader->localName ) : // occurs 0-unbounded
                    $sieEntry->addJournal( $journalTypeEntryParser->parse());
                    break;

                case ( self::DOCUMENTS == $this->reader->localName ) :  // occurs 0-1
                    $sieEntry->setDocuments( DocumentsTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::SIGNATURE == $this->reader->localName ) :  // occurs 1
                    $sieEntry->setSignature( SignatureTypeParser::factory( $this->reader )->parse());
                    break;
            } // end switch
        } // end while

        return $sieEntry;
    }
}
