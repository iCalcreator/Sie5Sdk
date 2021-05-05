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
namespace Kigkonsult\Sie5Sdk\XMLWrite;

use Kigkonsult\Sie5Sdk\Dto\GeneralObjectType;

use function is_array;

class GeneralObjectTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param GeneralObjectType $generalObjectType
     *
     */
    public function write( GeneralObjectType $generalObjectType )
    {
        $XMLattributes = $generalObjectType->getXMLattributes();
        parent::setWriterStartElement( $this->writer, self::GENERALOBEJCT, $XMLattributes );

        parent::writeAttribute( $this->writer, self::ID,   $generalObjectType->getId());
        parent::writeAttribute( $this->writer, self::NAME, $generalObjectType->getName());
        foreach( $generalObjectType->getExtensionAttributes() as $key => $value ) {
            if( self::TYPE == $key ) {
                $key = self::XSITYPE;
            }
            parent::writeAttribute( $this->writer, $key, $value );
        }

        $balances = $generalObjectType->getBalances();
        if( is_array( $balances ) && ! empty( $balances )) {
            $writer = new BalancesTypeWriter( $this->writer );
            foreach( $balances as $element ) {
                $writer->write( $element );
            }
        } // end if

        $originalAmount = $generalObjectType->getOriginalAmount();
        if( null != $originalAmount ) {
            OriginalAmountTypeWriter::factory( $this->writer )->write( $originalAmount );
        }

        $this->writer->endElement();
    }
}
