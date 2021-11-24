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
namespace Kigkonsult\Sie5Sdk\XMLParse;

use Kigkonsult\Sie5Sdk\Dto\SupplierType;

use function count;
use function implode;
use function sprintf;

class SupplierTypeParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return SupplierType
     */
    public function parse() : SupplierType
    {
        $supplierType = SupplierType::factory()->setXMLattributes( $this->reader );
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
                        $supplierType->setId( $this->reader->value );
                        break;
                    case self::NAME :
                        $supplierType->setName( $this->reader->value );
                        break;
                    case self::ORGANIZATIONID :
                        $supplierType->setOrganizationId( $this->reader->value );
                        break;
                    case self::VATNR :
                        $supplierType->setVatNr( $this->reader->value );
                        break;
                    case self::ADDRESS1 :
                        $supplierType->setAddress1( $this->reader->value );
                        break;
                    case self::ADDRESS2 :
                        $supplierType->setAddress2( $this->reader->value );
                        break;
                    case self::ZIPCODE :
                        $supplierType->setZipcode( $this->reader->value );
                        break;
                    case self::CITY :
                        $supplierType->setCity( $this->reader->value );
                        break;
                    case self::COUNTRY :
                        $supplierType->setCountry( $this->reader->value );
                        break;
                    case self::BGACCOUNT :
                        $supplierType->setBgAccount( $this->reader->value );
                        break;
                    case self::PGACCOUNT :
                        $supplierType->setPgAccount( $this->reader->value );
                        break;
                    case self::BIC :
                        $supplierType->setBic( $this->reader->value );
                        break;
                    case self::IBAN :
                        $supplierType->setIban( $this->reader->value );
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
                $supplierType->setExtensionAttributes( $extensionAttributes );
            }
            $this->reader->moveToElement();
        } // end if
        return $supplierType;
    }
}
