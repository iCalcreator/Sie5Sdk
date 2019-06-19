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

use Kigkonsult\Sie5Sdk\Dto\GeneralSubdividedAccountTypeEntry;

class GeneralSubdividedAccountTypeEntryWriter extends Sie5WriterBase implements Sie5WriterInterface
{

    /**
     * Write
     * @param GeneralSubdividedAccountTypeEntry $generalSubdividedAccountTypeEntry
     *
     */
    public function write( GeneralSubdividedAccountTypeEntry $generalSubdividedAccountTypeEntry ) {
        $XMLattributes = $generalSubdividedAccountTypeEntry->getXMLattributes();;
        parent::SetWriterStartElement( $this->writer, self::GENERALSUBDIVIDEDACCOUNT, $XMLattributes );

        parent::writeAttribute(
            $this->writer, self::PRIMARYACCOUNTID, $generalSubdividedAccountTypeEntry->getPrimaryAccountId()
        );
        parent::writeAttribute( $this->writer, self::NAME, $generalSubdividedAccountTypeEntry->getName());

        $writer = new GeneralObjectTypeEntryWriter( $this->writer );
        foreach( $generalSubdividedAccountTypeEntry->getGeneralObject() as  $element ) {
            $writer->write( $element );
        }

        $this->writer->endElement();
    }
}