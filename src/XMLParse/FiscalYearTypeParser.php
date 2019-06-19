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
namespace Kigkonsult\Sie5Sdk\XMLParse;

use Kigkonsult\Sie5Sdk\Dto\FiscalYearType;
use DateTime;
use Exception;

use function sprintf;

class FiscalYearTypeParser extends Sie5ParserBase
{

    /**
     * Parse
     *
     * @return FiscalYearType
     * @throws Exception
     */
    public function parse() {
        $fiscalYearType = FiscalYearType::factory()->setXMLattributes( $this->reader );
        $this->logger->debug(
            sprintf( self::$FMTstartNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
        );
        if( $this->reader->hasAttributes ) {
            while( $this->reader->moveToNextAttribute()) {
                $this->logger->debug(
                    sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                );
                switch( $this->reader->name ) {
                    case self::START :
                        $fiscalYearType->setStart( $this->reader->value );
                        break;
                    case self::END :
                        $fiscalYearType->setEnd( $this->reader->value );
                        break;
                    case self::PRIMARY :
                        $fiscalYearType->setPrimary( self::TRUE == $this->reader->value );
                        break;
                    case self::CLOSED :
                        $fiscalYearType->setClosed( self::TRUE == $this->reader->value );
                        break;
                    case self::HASLEDGERENTRIES :
                        $fiscalYearType->setHasLedgerEntries( self::TRUE == $this->reader->value );
                        break;
                    case self::HASSUBORDINATEACCOUNTS :
                        $fiscalYearType->setHasSubordinateAccounts( self::TRUE == $this->reader->value );
                        break;
                    case self::HASATTACHEDVOUCHERFILES :
                        $fiscalYearType->setHasAttachedVoucherFiles( self::TRUE == $this->reader->value );
                        break;
                    case self::LASTCOVEREDDATE :
                        try {
                            $fiscalYearType->setLastCoveredDate( new DateTime( $this->reader->value ));
                        } catch( Exception $e ) {
                            $this->logger->error(
                                sprintf( parent::$FMTERRDATE, $this->reader->value )
                            );
                            throw $e;
                        }
                        break;
                    default :
                        break;
                } // end switch
            } // end while
            $this->reader->moveToElement();
        } // end if
        return $fiscalYearType;
    }
}
