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

use Kigkonsult\Sie5Sdk\Dto\LedgerEntryType;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

use function is_array;

class LedgerEntryTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param LedgerEntryType $ledgerEntryType
     *
     */
    public function write( LedgerEntryType $ledgerEntryType )
    {
        $XMLattributes = $ledgerEntryType->getXMLattributes();
        parent::setWriterStartElement( $this->writer, self::LEDGERENTRY, $XMLattributes );

        parent::writeAttribute( $this->writer, self::ACCOUNTID, $ledgerEntryType->getAccountId());
        parent::writeAttribute(
            $this->writer,
            self::AMOUNT,
            CommonFactory::formatAmount( $ledgerEntryType->getAmount())
        );
        $var = $ledgerEntryType->getQuantity();
        if( ! empty( $var )) {
            parent::writeAttribute( $this->writer, self::QUANTITY, $var );
        }
        $var = $ledgerEntryType->getText();
        if( ! empty( $var )) {
            parent::writeAttribute( $this->writer, self::TEXT, $var );
        }
        $ledgerDate = $ledgerEntryType->getLedgerDate();
        if( ! empty( $ledgerDate )) {
            self::writeAttribute(
                $this->writer, self::LEDGERDATE, $ledgerDate->format( self::FMTDATE )
            );
        }
        foreach( $ledgerEntryType->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE == $key ) {
                $key = self::XSITYPE;
            }
            parent::writeAttribute( $this->writer, $key, $value );
        }

        $ledgerEntryTypes = $ledgerEntryType->getLedgerEntryTypes();
        if( is_array( $ledgerEntryTypes ) && ! empty( $ledgerEntryTypes )) {
            $foreignCurrencyAmountTypeWriter            = new ForeignCurrencyAmountTypeWriter( $this->writer );
            $objectReferenceTypeWriter                  = new ObjectReferenceTypeWriter( $this->writer );
            $subdividedAccountObjectReferenceTypeWriter = new SubdividedAccountObjectReferenceTypeWriter( $this->writer );
            $entryInfoTypeWriter                        = new EntryInfoTypeWriter( $this->writer );
            $overstrikeTypeWriter                       = new OverstrikeTypeWriter( $this->writer );
            $lockingInfoTypeWriter                      = new LockingInfoTypeWriter( $this->writer );
            foreach( $ledgerEntryTypes as $elementSet ) {
                foreach( $elementSet as $element ) {
                    foreach( $element as $key => $value ) {
                        switch( $key ) {
                            case self::FOREIGNCURRENCYAMOUNT :
                                $foreignCurrencyAmountTypeWriter->write( $value );
                                break;
                            case self::OBJECTREFERENCE :
                                $objectReferenceTypeWriter->write( $value );
                                break;
                            case self::SUBDIVIDEDACCOUNTOBJECTREFERENCE :
                                $subdividedAccountObjectReferenceTypeWriter->write( $value );
                                break;
                            case self::ENTRYINFO :
                                $entryInfoTypeWriter->write( $value );
                                break;
                            case self::OVERSTRIKE :
                                $overstrikeTypeWriter->write( $value );
                                break;
                            case self::LOCKINGINFO :
                                $lockingInfoTypeWriter->write( $value );
                                break;
                        } // end switch
                    } // end foreach
                }  // end foreach
            } // end foreach
        } // end if

        $this->writer->endElement();
    }
}
