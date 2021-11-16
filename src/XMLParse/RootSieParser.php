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
namespace Kigkonsult\Sie5Sdk\XMLParse;

use Exception;
use Kigkonsult\DsigSdk\XMLParse\SignatureTypeParser;
use Kigkonsult\Sie5Sdk\Dto\Sie;
use XMLReader;

use function sprintf;

class RootSieParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return Sie
     * @throws Exception
     *
     */
    public function parse() : Sie
    {
        $sie = Sie::factory()->setXMLattributes( $this->reader );
        $this->logger->debug(
            sprintf( self::$FMTstartNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
        );
        if( $this->reader->hasAttributes ) {
            while( $this->reader->moveToNextAttribute()) {
                $this->logger->debug(
                    sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                );
                $sie->setXMLattribute( $this->reader->name, $this->reader->value );
            }
            $this->reader->moveToElement();
        } // end if
        if( $this->reader->isEmptyElement ) {
            return $sie;
        }
        $headElement = $this->reader->localName;
        $customerInvoicesTypeParser = new CustomerInvoicesTypeParser( $this->reader );
        $supplierInvoicesTypeParser = new SupplierInvoicesTypeParser( $this->reader );
        $fixedAssetsTypeParser      = new FixedAssetsTypeParser( $this->reader );
        $generalSubdividedAccountTypeParser = new GeneralSubdividedAccountTypeParser( $this->reader );
        $journalTypeParser          = new JournalTypeParser( $this->reader );
        while( @$this->reader->read()) {
            if( XMLReader::SIGNIFICANT_WHITESPACE !== $this->reader->nodeType ) {
                $this->logger->debug(
                    sprintf( self::$FMTreadNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
                );
            }
            switch( true ) {
                case ( XMLReader::END_ELEMENT === $this->reader->nodeType ) :
                    if( $headElement === $this->reader->localName ) {
                        break 2;
                    }
                    break;
                case ( XMLReader::ELEMENT !== $this->reader->nodeType ) :
                    break;

                case ( self::FILEINFO === $this->reader->localName ) :  // occurs 1
                    $sie->setFileInfo( FileInfoTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::ACCOUNTS === $this->reader->localName ) :  // occurs 1
                    $sie->setAccounts( AccountsTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::DIMENSIONS === $this->reader->localName ) :  // occurs 0-1
                    $sie->setDimensions( DimensionsTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::CUSTOMERINVOICES === $this->reader->localName ) : // occurs 0-unbounded
                    $sie->addCustomerInvoices( $customerInvoicesTypeParser->parse());
                    break;

                case ( self::SUPPLIERINVOICES === $this->reader->localName ) : // occurs 0-unbounded
                    $sie->addSupplierInvoices( $supplierInvoicesTypeParser->parse());
                    break;
                case ( self::FIXEDASSETS === $this->reader->localName ) : // occurs 0-unbounded
                    $sie->addFixedAsset( $fixedAssetsTypeParser->parse());
                    break;

                case ( self::GENERALSUBDIVIDEDACCOUNT === $this->reader->localName ) : // occurs 0-unbounded
                    $sie->addGeneralSubdividedAccount( $generalSubdividedAccountTypeParser->parse());
                    break;

                case ( self::CUSTOMERS === $this->reader->localName ) :  // occurs 0-1
                    $sie->setCustomers( CustomersTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::SUPPLIERS === $this->reader->localName ) :  // occurs 0-1
                    $sie->setSuppliers( SuppliersTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::ACCOUNTAGGREGATIONS === $this->reader->localName ) :  // occurs 0-1
                    $sie->setAccountAggregations( AccountAggregationsTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::JOURNAL === $this->reader->localName ) : // occurs 0-unbounded
                    $sie->addJournal( $journalTypeParser->parse());
                    break;

                case ( self::DOCUMENTS === $this->reader->localName ) :  // occurs 0-1
                    $sie->setDocuments( DocumentsTypeParser::factory( $this->reader )->parse());
                    break;

                case ( self::SIGNATURE === $this->reader->localName ) :  // occurs 1
                    $sie->setSignature( SignatureTypeParser::factory( $this->reader )->parse());
                    break;
            } // end switch
        } // end while
        return $sie;
    }
}
