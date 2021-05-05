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

use Kigkonsult\Sie5Sdk\Dto\LockingInfoType;
use DateTime;
use Exception;

class LockingInfoTypeParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return LockingInfoType
     * @throws Exception
     */
    public function parse() : LockingInfoType
    {
        $lockingInfoType = LockingInfoType::factory()->setXMLattributes( $this->reader );
        $this->logger->debug(
            sprintf( self::$FMTstartNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
        );
            if( $this->reader->hasAttributes ) {
                while( $this->reader->moveToNextAttribute()) {
                    $this->logger->debug(
                        sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                    );
                    switch( $this->reader->name ) {
                        case self::DATE :
                            try {
                                $lockingInfoType->setDate( new DateTime( $this->reader->value ));
                            }
                            catch( Exception $e ) {
                                $this->logger->error(
                                    sprintf( parent::$FMTERRDATE, $this->reader->value )
                                );
                                throw $e;
                            }
                            break;
                        case self::BY :
                            $lockingInfoType->setBy( $this->reader->value );
                            break;
                    } // end switch
                } // end while
                $this->reader->moveToElement();
            } // end if
        return $lockingInfoType;
    }
}
