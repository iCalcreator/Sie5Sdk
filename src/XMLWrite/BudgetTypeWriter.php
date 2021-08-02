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

use Kigkonsult\Sie5Sdk\Dto\BudgetType;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

use function is_array;

class BudgetTypeWriter extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write
     *
     * @param BudgetType $budgetType
     *
     */
    public function write( BudgetType $budgetType )
    {
        $XMLattributes = $budgetType->getXMLattributes();
        parent::setWriterStartElement( $this->writer, self::BUDGET, $XMLattributes );

        parent::writeAttribute( $this->writer, self::MONTH,    $budgetType->getMonth());
        $amount = $budgetType->getAmount();
        if( ! empty( $amount ) || ( 0.0 === $amount )) {
            parent::writeAttribute(
                $this->writer,
                self::AMOUNT,
                CommonFactory::formatAmount( $amount )
            );
        }
        $quantity = $budgetType->getQuantity();
        if( ! empty( $quantity )) {
            parent::writeAttribute($this->writer, self::QUANTITY, $quantity );
        }

        $objectReferences = $budgetType->getObjectReference();
        if( is_array( $objectReferences ) && ! empty( $objectReferences )) {
            $writer = new ObjectReferenceTypeWriter( $this->writer );
            foreach( $objectReferences as $element ) {
                $writer->write( $element );
            }
        } // end if

        $this->writer->endElement();
    }
}
