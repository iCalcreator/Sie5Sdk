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

use Kigkonsult\Sie5Sdk\Dto\BaseBalanceMultidimType;
use XMLReader;

use function sprintf;

class BaseBalanceMultidimTypeParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return BaseBalanceMultidimType
     */
    public function parse() : BaseBalanceMultidimType
    {
        $baseBalanceMultidimType = BaseBalanceMultidimType::factory()->setXMLattributes( $this->reader );
        $this->logger->debug(
            sprintf( self::$FMTstartNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
        );
        if( $this->reader->hasAttributes ) {
            while( $this->reader->moveToNextAttribute()) {
                $this->logger->debug(
                    sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                );
                switch( $this->reader->name ) {
                    case self::MONTH :
                        $baseBalanceMultidimType->setMonth( $this->reader->value );
                        break;
                    case self::AMOUNT :
                        $baseBalanceMultidimType->setAmount( $this->reader->value );
                        break;
                    case self::QUANTITY :
                        $baseBalanceMultidimType->setQuantity( $this->reader->value );
                        break;
                } // end switch
            } // end while
            $this->reader->moveToElement();
        } // end if
        if( $this->reader->isEmptyElement ) {
            return $baseBalanceMultidimType;
        }
        $headElement               = $this->reader->localName;
        $foreignCurrencyAmountTypeParser = new ForeignCurrencyAmountTypeParser( $this->reader );
        $objectReferenceTypeParser = new ObjectReferenceTypeParser( $this->reader );
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
                case ( self::FOREIGNCURRENCYAMOUNT === $this->reader->localName ) :
                    $baseBalanceMultidimType->addBaseBalanceMultidimType(
                        self::FOREIGNCURRENCYAMOUNT,
                        $foreignCurrencyAmountTypeParser->parse()
                    );
                    break;
                case ( self::OBJECTREFERENCE === $this->reader->localName ) :
                    $baseBalanceMultidimType->addBaseBalanceMultidimType(
                        self::OBJECTREFERENCE,
                        $objectReferenceTypeParser->parse()
                    );
                    break;
            } // end switch
        } // end while
        return $baseBalanceMultidimType;
    }
}
