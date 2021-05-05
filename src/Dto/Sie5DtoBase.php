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
namespace Kigkonsult\Sie5Sdk\Dto;

use InvalidArgumentException;
use Kigkonsult\LoggerDepot\LoggerDepot;
use Kigkonsult\Sie5Sdk\Sie5Interface;
use Kigkonsult\Sie5Sdk\Sie5XMLAttributesInterface;
use Psr\Log\LogLevel;
use XMLReader;

use function get_called_class;
use function get_class;
use function get_class_vars;
use function is_array;
use function is_null;
use function is_object;
use function is_scalar;
use function method_exists;
use function str_replace;
use function var_export;

abstract class Sie5DtoBase extends LogLevel implements Sie5Interface, Sie5XMLAttributesInterface
{
    /**
     * @var string
     */
    protected static $FMTERR1   = 'Unknown %s type #%s \'%s\'';
    protected static $FMTERR3   = '%s type \'%s\' requires 2+';
    protected static $FMTERR4   = '%s type #%s \'%s\' requires 2+';
    protected static $FMTERR11  = '%s::%s (%s) is not unique';
    protected static $FMTERR111 = '%s::%s #%d (%s) is not unique';
    protected static $FMTERR112 = '%s::%s #%d-%d (%s) is not unique';
    protected static $FMTERR12  = '%s::%s (%s-%s) is not unique';
    protected static $FMTERR5   = '%s Imparity error key \'%s\' and type \'%s\'';
    protected static $FMTERR51  = '%s #%d Imparity error key \'%s\' and type \'%s\'';
    protected static $FMTERR52  = '%s #%d-%d Imparity error key \'%s\' and type \'%s\'';
    protected static $OBJECT    = 'object';

    /**
     * @var mixed
     */
    protected $logger = null;

    /**
     * @var array
     */
    protected $XMLattributes = [];

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        static $BS = '\\';
        $this->logger = LoggerDepot::getLogger( get_class( $this ));
        // assure localName is set, otherwise (re-)set in Sie5Writer*, if used
        $this->setXMLattribute(
            self::LOCALNAME,
            str_replace( [ __NAMESPACE__, $BS ], '', get_class( $this ))
        );
    }

    /**
     * Class factory
     *
     * @return static
     */
    public static function factory() : self
    {
        $class = get_called_class();
        return new $class();
    }

    /**
     * Return XML attributes
     *
     * @return array
     */
    public function getXMLattributes() : array
    {
        return $this->XMLattributes;
    }

    /**
     * Set XML attributes, if prefix, travers down and set
     *
     * @param string $name
     * @param string $value
     * @return static
     * @throws InvalidArgumentException
     */
    public function setXMLattribute( string $name, string $value ) : self
    {
        $this->XMLattributes[$name] = $value;
        if( self::PREFIX == $name ) {
            self::traversPrefixDown( $this, $value );
        }
        return $this;
    }

    /**
     * Set prefix in properties
     *
     * @param Sie5DtoBase $sie5DtoBase (if Sie5DtoBase ), if array, travers down
     * @param string $value
     * @static
     */
    protected static function traversPrefixDown( Sie5DtoBase $sie5DtoBase, string $value )
    {
        foreach( get_object_vars( $sie5DtoBase ) as $propertyValue ) {
            if( $propertyValue instanceof Sie5DtoBase ) {
                $propertyValue->setXMLattribute( self::PREFIX,  $value );
            }
            elseif( is_array( $propertyValue )) {
                self::traversPrefixDownArray( $propertyValue, $value );
            } // end if
        } // end foreach
    }

    /**
     * Set prefix in <Sie5DtoBase>::property array
     *
     * @param array $arrayValue
     * @param string $value
     * @static
     */
    protected static function traversPrefixDownArray( array $arrayValue, string $value )
    {
        foreach( $arrayValue as $array2Value ) {
            if( $array2Value instanceof Sie5DtoBase ) {
                $array2Value->setXMLattribute( self::PREFIX,  $value );
            }
            elseif( is_array( $array2Value )) {
                self::traversPrefixDownArray( $array2Value, $value );
            } // end if
        } // end foreach
    }

    /**
     * Set XML attributes from XMLReader and return attributes, overwrites prev set localName
     *
     * @param XMLReader $reader Element node
     * @return static
     */
    public function setXMLattributes( XMLReader $reader ) : self
    {
        $this->XMLattributes[self::BASEURI]      = $reader->baseURI;
        $this->XMLattributes[self::LOCALNAME]    = $reader->localName;
        $this->XMLattributes[self::NAME]         = $reader->name;
        $this->XMLattributes[self::NAMESPACEURI] = $reader->namespaceURI;
        $this->XMLattributes[self::PREFIX]       = $reader->prefix;
        return $this;
    }

    /**
     * Return string
     *
     * @return string
     */
    public function toString() : string
    {
        return Sie5DtoBase::dispObject( $this );
    }

    /**
     * Return string
     *
     * @param Sie5DtoBase $instance
     * @return string
     */
    protected static function dispObject( Sie5DtoBase $instance ) : string
    {
        $class   = get_class( $instance );
        $string  = 'start ' . $class . ' -----v' . PHP_EOL;
        $string .= 'XMLattributes : ' .
            str_replace( [PHP_EOL, ' '], '', var_export( $instance->getXMLattributes(), true )) .
            PHP_EOL;
        foreach( get_class_vars( $instance ) as $property => $value ) {
            if( 'XMLattributes' == $property ) {
                continue;
            }
            switch( true ) {
                case ( is_null( $value )) :
                    $string .= $property . ' : ' . PHP_EOL;
                    break;
                case ( is_scalar( $value )) :
                    $string .= $property . ' : ' . ( empty( $value ) ? '' : $value ) . PHP_EOL;
                    break;
                case ( is_array( $value )) :
                    $string .= Sie5DtoBase::dispArray( $property, $value );
                    break;
                case ( is_object( $value )) :
                    $string .= $property . ' : ';
                    $string .= ( method_exists( $value, 'toString' ))
                        ? PHP_EOL . $value->toString()
                        : '??' . PHP_EOL;
                    break;
                default :
                    $string .= $property . ' : ' . PHP_EOL . var_export( $value, true ) . PHP_EOL;
            } // end switch
        } // end foreach
        $string .= 'end ' . $class . '-----^' . PHP_EOL;
        return $string;
    }

    /**
     * Return string
     *
     * @param string $property
     * @param array $value
     * @return string
     */
    protected static function dispArray( string $property, array $value ) : string
    {
        $string = null;
        foreach( $value as $key2 => $value2 ) {
            switch( true ) {
                case ( is_scalar( $value2 )) :
                    $string .= $property . '[' . $key2 . '] : ' . ( empty( $value2 ) ? '' : $value2 ) . PHP_EOL;
                    break;
                case ( is_array( $value2 )) :
                    foreach( $value2 as $key3 => $value3 ) {
                        $string .= $property . '[' . $key2 . '][' . $key3 .  '] : ' .
                            ( empty( $value3 ) ? '' : $value3 ) . PHP_EOL;
                    }
                    break;
                case ( is_object( $value2 )) :
                    $string .= $property . ' : ';
                    $string .= ( method_exists( $value2, 'toString' ))
                        ? PHP_EOL . $value2->toString()
                        : '??' . PHP_EOL;
                    break;
                default :
                    $string .= $property . '[' . $key2 . '] : ' . var_export( $value2, true ) . PHP_EOL;
                    break;
            } // end switch
        } // end foreach
        return $string;
    }
}
