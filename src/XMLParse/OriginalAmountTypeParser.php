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

use Kigkonsult\Sie5Sdk\Dto\OriginalAmountType;
use DateTime;
use Exception;
use XMLReader;

use function sprintf;

class OriginalAmountTypeParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return OriginalAmountType
     * @throws Exception
     */
    public function parse() : OriginalAmountType
    {
        $originalAmountType = OriginalAmountType::factory()->setXMLattributes( $this->reader );
        $this->logger->debug(
            sprintf( self::$FMTstartNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
        );
        if( $this->reader->hasAttributes ) {
            while( $this->reader->moveToNextAttribute()) {
                $this->logger->debug(
                    sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                );
                switch( $this->reader->name ) {
                    case ( self::DATE ) :
                        try {
                            $originalAmountType->setDate( new DateTime( $this->reader->value ));
                        }
                        catch( Exception $e ) {
                            $this->logger->error(
                                sprintf( parent::$FMTERRDATE, $this->reader->value )
                            );
                            throw $e;
                        }
                        break;
                    case ( self::AMOUNT ) :
                        $originalAmountType->setAmount( $this->reader->value );
                        break;
                } // end switch
            } // end while
            $this->reader->moveToElement();
        } // end if
        if( $this->reader->isEmptyElement ) {
            return $originalAmountType;
        }
        $headElement = $this->reader->localName;
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
                    // sequence maxOccurs="1" minOccurs="0"
                case ( self::FOREIGNCURRENCYAMOUNT === $this->reader->localName ) :
                    $originalAmountType->setForeignCurrencyAmount(
                        ForeignCurrencyAmountTypeParser::factory( $this->reader )->parse()
                    );
                    break;
            } // end switch
        } // while
        return $originalAmountType;
    }
}
