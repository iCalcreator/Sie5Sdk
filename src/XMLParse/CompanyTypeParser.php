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

use Kigkonsult\Sie5Sdk\Dto\CompanyType;

use function count;
use function implode;
use function sprintf;

class CompanyTypeParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return CompanyType
     */
    public function parse() : CompanyType
    {
        $companyType = CompanyType::factory()->setXMLattributes( $this->reader );
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
                    case self::ORGANIZATIONID :
                        $companyType->setOrganizationId( $this->reader->value );
                        break;
                    case self::MULTIPLE :
                        $companyType->setMultiple( $this->reader->value );
                        break;
                    case self::NAME :
                        $companyType->setName( $this->reader->value );
                        break;
                    case self::CLIENTID :
                        $companyType->setClientId( $this->reader->value );
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
                $companyType->setExtensionAttributes( $extensionAttributes );
            }
            $this->reader->moveToElement();
        } // end if
        return $companyType;
    }
}
