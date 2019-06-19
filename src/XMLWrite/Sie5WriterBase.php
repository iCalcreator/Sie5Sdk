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

use Kigkonsult\LoggerDepot\LoggerDepot;
use Kigkonsult\Sie5Sdk\Sie5Interface;
use Kigkonsult\Sie5Sdk\Sie5XMLAttributesInterface;
use Psr\Log\LogLevel;
use XMLWriter;

use function get_called_class;
use function in_array;
use function is_null;
use function sprintf;
use function substr;

abstract class Sie5WriterBase extends LogLevel implements Sie5Interface, Sie5XMLAttributesInterface
{

    /**
     * @var mixed
     * @access protected
     */
    protected $logger = null;

    /**
     * @var XMLWriter
     * @access protected
     */
    protected $writer = null;

    /**
     * Constructor
     *
     * @param XMLWriter $writer
     */
    public function __construct( XMLWriter $writer = null ) {
        $this->logger = LoggerDepot::getLogger( __CLASS__ );
        if( ! is_null( $writer )) {
            $this->writer = $writer;
        }
    }

    /**
     * Factory
     *
     * @param XMLWriter $writer
     * @return static
     * @static
     */
    public static function factory( $writer = null ) {
        $class = get_called_class();
        return new $class( $writer );
    }

    /**
     * Set writer start element
     *
     * @param XMLWriter $writer
     * @param string    $elementName
     * @param array     $XMLattributes
     * @access protected
     * @static
     */
    protected static function SetWriterStartElement( XMLWriter $writer, $elementName = null, array $XMLattributes = [] ) {
        $FMTNAME = '%s:%s';
        if( empty( $elementName )) {
            $elementName = $XMLattributes[self::LOCALNAME]; // auto set in Sie5DtoBase
        }
        if( isset( $XMLattributes[self::PREFIX] ) && ! empty( $XMLattributes[self::PREFIX] )) {
            $elementName = sprintf( $FMTNAME, $XMLattributes[self::PREFIX], $elementName );
        }
        $writer->startElement( $elementName );
        foreach( $XMLattributes as $key => $value ) {
            if( in_array( $key, self::XMLSchemaKeys ) ||
                ( self::XMLNS == substr( $key, 0, 5 ))) {
                self::writeAttribute( $writer, $key, $value );
            }
        }
    }

    /**
     * Write attribute
     *
     * @param XMLWriter $writer
     * @param string    $elementName
     * @param string    $value
     * @access protected
     * @static
     */
    protected static function writeAttribute( XMLWriter $writer, $elementName, $value ) {
        if( ! is_null( $value )) {
            $writer->startAttribute($elementName );
            $writer->text( $value );
            $writer->endAttribute();
        }
    }

}