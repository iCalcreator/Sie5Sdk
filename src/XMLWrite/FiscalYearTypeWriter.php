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

use Kigkonsult\Sie5Sdk\Dto\FiscalYearType;

use function is_bool;

class FiscalYearTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param FiscalYearType $fiscalYearType
     *
     */
    public function write( FiscalYearType $fiscalYearType ) : void
    {
        $XMLattributes = $fiscalYearType->getXMLattributes();
        self::setWriterStartElement( $this->writer, self::FISCALYEAR, $XMLattributes );

        self::writeAttribute( $this->writer, self::START, $fiscalYearType->getStart() );
        self::writeAttribute( $this->writer, self::END, $fiscalYearType->getEnd() );
        $bool = $fiscalYearType->getPrimary();
        if( is_bool( $bool )) {
            self::writeAttribute( $this->writer,
                self::PRIMARY,
                ( $bool ? self::TRUE : self::FALSE ) );
        }
        $bool = $fiscalYearType->getClosed();
        if( is_bool( $bool )) {
            self::writeAttribute( $this->writer,
                self::CLOSED,
                ( $bool ? self::TRUE : self::FALSE ) );
        }
        $bool = $fiscalYearType->getHasLedgerEntries();
        if( is_bool( $bool )) {
            self::writeAttribute( $this->writer,
                self::HASLEDGERENTRIES,
                ( $bool ? self::TRUE : self::FALSE ) );
        }
        $bool = $fiscalYearType->getHasSubordinateAccounts();
        if( is_bool( $bool )) {
            self::writeAttribute( $this->writer,
                self::HASSUBORDINATEACCOUNTS,
                ( $bool ? self::TRUE : self::FALSE ) );
        }
        $bool = $fiscalYearType->getHasAttachedVoucherFiles();
        if( is_bool( $bool )) {
            self::writeAttribute( $this->writer,
                self::HASATTACHEDVOUCHERFILES,
                ( $bool ? self::TRUE : self::FALSE ) );
        }
        $lastCoveredDate = $fiscalYearType->getLastCoveredDate();
        if( ! empty( $lastCoveredDate )) {
            self::writeAttribute(
                $this->writer,
                self::LASTCOVEREDDATE,
                $lastCoveredDate->format( self::FMTDATE )
            );
        } // end if

        $this->writer->endElement();
    }
}
