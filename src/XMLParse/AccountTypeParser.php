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

use Kigkonsult\Sie5Sdk\Dto\AccountType;
use XMLReader;

use function count;
use function implode;
use function sprintf;

class AccountTypeParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return AccountType
     */
    public function parse() : AccountType
    {
        $accountType  = AccountType::factory()->setXMLattributes( $this->reader );
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
                        $accountType->setId( $this->reader->value );
                        break;
                    case self::NAME :
                        $accountType->setName( $this->reader->value );
                        break;
                    case self::TYPE :
                        $accountType->setType( $this->reader->value );
                        break;
                    case self::UNIT :
                        $accountType->setUnit( $this->reader->value );
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
                $accountType->setExtensionAttributes( $extensionAttributes ); // will not work here ...
            }
            $this->reader->moveToElement();
        } // end if
        if( $this->reader->isEmptyElement ) {
            return $accountType;
        }
        $headElement = $this->reader->localName;
        $baseBalanceTypeParser         = new BaseBalanceTypeParser( $this->reader );
        $budgetTypeParser              = new BudgetTypeParser( $this->reader );
        $baseBalanceMultidimTypeParser = new BaseBalanceMultidimTypeParser( $this->reader );
        $budgetMultidimTypeParser      = new BudgetMultidimTypeParser( $this->reader );
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
                case ( self::OPENINGBALANCE === $this->reader->localName ) :
                    $accountType->addAccountType( self::OPENINGBALANCE, $baseBalanceTypeParser->parse());
                    break;
                case ( self::CLOSINGBALANCE === $this->reader->localName ) :
                    $accountType->addAccountType( self::CLOSINGBALANCE, $baseBalanceTypeParser->parse());
                    break;
                case ( self::BUDGET === $this->reader->localName ) :
                    $accountType->addAccountType( self::BUDGET, $budgetTypeParser->parse());
                    break;
                case ( self::OPENINGBALANCEMULTIDIM === $this->reader->localName ) :
                    $accountType->addAccountType(
                        self::OPENINGBALANCEMULTIDIM, $baseBalanceMultidimTypeParser->parse()
                    );
                    break;
                case ( self::CLOSINGBALANCEMULTIDIM === $this->reader->localName ) :
                    $accountType->addAccountType(
                        self::CLOSINGBALANCEMULTIDIM, $baseBalanceMultidimTypeParser->parse()
                    );
                    break;
                case ( self::BUDGETMULTIDIM === $this->reader->localName ) :
                    $accountType->addAccountType( self::BUDGETMULTIDIM, $budgetMultidimTypeParser->parse());
                    break;
            } // end switch
        } // end while
        return $accountType;
    }
}
