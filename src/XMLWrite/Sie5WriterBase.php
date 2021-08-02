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

use Kigkonsult\LoggerDepot\LoggerDepot;
use Kigkonsult\Sie5Sdk\Sie5Interface;
use Kigkonsult\Sie5Sdk\Sie5XMLAttributesInterface;
use Psr\Log\LogLevel;
use XMLWriter;

use function get_called_class;
use function sprintf;
use function strtolower;
use function substr;

abstract class Sie5WriterBase extends LogLevel implements Sie5Interface, Sie5XMLAttributesInterface
{
    /**
     * @var mixed
     */
    protected $logger = null;

    /**
     * @var XMLWriter
     */
    protected $writer = null;

    /**
     * Constructor
     *
     * @param XMLWriter $writer
     */
    public function __construct( XMLWriter $writer = null )
    {
        $this->logger = LoggerDepot::getLogger( __CLASS__ );
        if( null !== $writer ) {
            $this->writer = $writer;
        }
    }

    /**
     * Factory
     *
     * @param XMLWriter $writer
     * @return static
     */
    public static function factory( XMLWriter $writer = null ) : self
    {
        $class = get_called_class();
        return new $class( $writer );
    }

    /**
     * Set writer start element
     *
     * @param XMLWriter $writer
     * @param string    $elementName
     * @param array     $XMLattributes
     */
    protected static function setWriterStartElement(
        XMLWriter $writer,
        string $elementName = null,
        array $XMLattributes = []
    )
    {
        $FMTNAME = '%s:%s';
        if( empty( $elementName )) {
            $elementName = $XMLattributes[self::LOCALNAME]; // auto set in Sie5DtoBase
        }
        if( isset( $XMLattributes[self::PREFIX] ) && ! empty( $XMLattributes[self::PREFIX] )) {
            $elementName = sprintf( $FMTNAME, $XMLattributes[self::PREFIX], $elementName );
        }
        $writer->startElement( $elementName );
        foreach( $XMLattributes as $key => $value ) {
            $found = false;
            $lKey  = strtolower( $key );
            foreach( self::XMLSCHEMAKEYS as $schemaKey ) {
                if( $lKey == strtolower( $schemaKey )) {
                    $found = true;
                    break;
                }
            } // end foreach
            if( $found || ( self::XMLNS == substr( $key, 0, 5 ))) {
                self::writeAttribute( $writer, $key, $value );
            }
        }
    }

    /**
     * Write attribute
     *
     * @param XMLWriter   $writer
     * @param string      $elementName
     * @param null|string $value
     */
    protected static function writeAttribute( XMLWriter $writer, string $elementName, $value = '' )
    {
        if( ! empty( $value )) {
            $writer->startAttribute($elementName );
            $writer->text((string) $value );
            $writer->endAttribute();
        }
    }
}
