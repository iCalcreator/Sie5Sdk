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

use XMLWriter;
use Kigkonsult\Sie5Sdk\Sie5XMLAttributesInterface as XmlAttr;

trait ExtensionAttributeTrait
{
    /**
     * @param XMLWriter $writer
     * @param array     $XMLattributes
     */
    private static function writeXMLExtensionAttribute( XMLWriter $writer, array $XMLattributes )
    {
        if( isset( $XMLattributes[XmlAttr::XSITYPE] )) {
            Sie5WriterBase::writeAttribute(
                $writer,
                XmlAttr::XSITYPE,
                $XMLattributes[XmlAttr::XSITYPE]
            );
        }
    }

    /**
     * @param XMLWriter $writer
     * @param array     $extensionAttributes
     */
    private static function writeExtensionAttributes( XMLWriter $writer, array $extensionAttributes )
    {
        foreach( $extensionAttributes as $name => $value ) {
            Sie5WriterBase::writeAttribute( $writer, $name, $value );
        }
    }
}
