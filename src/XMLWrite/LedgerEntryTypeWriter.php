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
namespace Kigkonsult\Sie5Sdk\XMLWrite;

use Kigkonsult\Sie5Sdk\Dto\LedgerEntryType;

use function is_null;

class LedgerEntryTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{

    /**
     * Write
     * @param LedgerEntryType $ledgerEntryType
     *
     */
    public function write( LedgerEntryType $ledgerEntryType ) {
        $XMLattributes = $ledgerEntryType->getXMLattributes();;
        parent::SetWriterStartElement( $this->writer, self::LEDGERENTRY, $XMLattributes );

        parent::writeAttribute( $this->writer, self::ACCOUNTID, $ledgerEntryType->getAccountId());
        parent::writeAttribute( $this->writer, self::AMOUNT,    $ledgerEntryType->getAmount());
        parent::writeAttribute( $this->writer, self::QUANTITY,  $ledgerEntryType->getQuantity());
        parent::writeAttribute( $this->writer, self::TEXT,      $ledgerEntryType->getText());
        $ledgerDate = $ledgerEntryType->getLedgerDate();
        if( ! is_null( $ledgerDate )) {
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

        $foreignCurrencyAmountTypeWriter = new ForeignCurrencyAmountTypeWriter( $this->writer );
        $objectReferenceTypeWriter       = new ObjectReferenceTypeWriter( $this->writer );
        $subdividedAccountObjectReferenceTypeWriter = new SubdividedAccountObjectReferenceTypeWriter( $this->writer );
        $entryInfoTypeWriter             = new EntryInfoTypeWriter( $this->writer );
        $overstrikeTypeWriter            = new OverstrikeTypeWriter( $this->writer );
        $lockingInfoTypeWriter           = new LockingInfoTypeWriter( $this->writer );
        foreach( $ledgerEntryType->getLedgerEntryTypes() as $elementSet ) {
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

        $this->writer->endElement();
    }
}
