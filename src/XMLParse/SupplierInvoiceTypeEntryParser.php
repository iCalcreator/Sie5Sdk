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

use Kigkonsult\Sie5Sdk\Dto\SupplierInvoiceTypeEntry;
use DateTime;
use Exception;

use function sprintf;

class SupplierInvoiceTypeEntryParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return SupplierInvoiceTypeEntry
     * @throws Exception
     */
    public function parse() : SupplierInvoiceTypeEntry
    {
        $supplierInvoiceTypeEntry = SupplierInvoiceTypeEntry::factory()->setXMLattributes( $this->reader );
        $this->logger->debug(
            sprintf( self::$FMTstartNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
        );
        if( $this->reader->hasAttributes ) {
            while( $this->reader->moveToNextAttribute()) {
                $this->logger->debug(
                    sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                );
                switch( $this->reader->name ) {
                    case self::ID :
                        $supplierInvoiceTypeEntry->setId( $this->reader->value );
                        break;
                    case self::NAME :
                        $supplierInvoiceTypeEntry->setName( $this->reader->value );
                        break;
                    case self::SUPPLIERID :
                        $supplierInvoiceTypeEntry->setSupplierId( $this->reader->value );
                        break;
                    case self::INVOICENUMBER :
                        $supplierInvoiceTypeEntry->setInvoiceNumber( $this->reader->value );
                        break;
                    case self::OCRNUMBER :
                        $supplierInvoiceTypeEntry->setOcrNumber( $this->reader->value );
                        break;
                    case self::DUEDATE :
                        try {
                            $supplierInvoiceTypeEntry->setDueDate( new DateTime( $this->reader->value ));
                        }
                        catch( Exception $e ) {
                            $this->logger->error(
                                sprintf( parent::$FMTERRDATE, $this->reader->value )
                            );
                            throw $e;
                        }
                        break;
                } // end switch
            } // end while
            $this->reader->moveToElement();
        } // end if

        return $supplierInvoiceTypeEntry;
    }
}
