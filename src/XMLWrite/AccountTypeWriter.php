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
namespace Kigkonsult\Sie5Sdk\XMLWrite;

use Kigkonsult\Sie5Sdk\Dto\AccountType;

use function is_array;

class AccountTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param AccountType $accountType
     *
     */
    public function write( AccountType $accountType )
    {
        $XMLattributes = $accountType->getXMLattributes();
        parent::setWriterStartElement( $this->writer, self::ACCOUNT, $XMLattributes );

        parent::writeAttribute( $this->writer, self::ID,   $accountType->getId());
        parent::writeAttribute( $this->writer, self::NAME, $accountType->getName());
        parent::writeAttribute( $this->writer, self::TYPE, $accountType->getType());
        $unit = $accountType->getUnit();
        if( ! empty( $unit )) {
            parent::writeAttribute($this->writer, self::UNIT, $unit );
        }
        foreach( $accountType->getExtensionAttributes() as $key => $value ) { // will not work here ...
            if( self::TYPE == $key ) {
                $key = self::XSITYPE;
            }
            parent::writeAttribute( $this->writer, $key, $value );
        }

        $accountTypes = $accountType->getAccountType();
        if( is_array( $accountTypes ) && ! empty( $accountTypes )) {
            $baseBalanceTypeWriter         = new BaseBalanceTypeWriter( $this->writer );
            $budgetTypeWriter              = new BudgetTypeWriter( $this->writer );
            $baseBalanceMultidimTypeWriter = new BaseBalanceMultidimTypeWriter( $this->writer );
            $budgetMultidimTypeWriter      = new BudgetMultidimTypeWriter( $this->writer );
            foreach( $accountTypes as $element ) {
                foreach( $element as $key => $value ) {
                    switch( $key ) {
                        case self::OPENINGBALANCE :
                            $baseBalanceTypeWriter->write( $value, self::OPENINGBALANCE );
                            break;
                        case self::CLOSINGBALANCE :
                            $baseBalanceTypeWriter->write( $value, self::CLOSINGBALANCE );
                            break;
                        case self::BUDGET :
                            $budgetTypeWriter->write( $value );
                            break;
                        case self::OPENINGBALANCEMULTIDIM :
                            $baseBalanceMultidimTypeWriter->write( $value, self::OPENINGBALANCEMULTIDIM );
                            break;
                        case self::CLOSINGBALANCEMULTIDIM :
                            $baseBalanceMultidimTypeWriter->write( $value, self::CLOSINGBALANCEMULTIDIM );
                            break;
                        case self::BUDGETMULTIDIM :
                            $budgetMultidimTypeWriter->write( $value );
                            break;
                    } // end switch
                } // end foreach
            } // end foreach
        } // end if

        $this->writer->endElement();
    }
}
