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
use Kigkonsult\Sie5Sdk\Impl\RenderFactory;
use Kigkonsult\Sie5Sdk\Sie5Interface;
use Kigkonsult\Sie5Sdk\Sie5XMLAttributesInterface;
use Psr\Log\LogLevel;
use XMLReader;

use function get_called_class;
use function get_class;
use function is_array;
use function str_replace;
use function sprintf;

abstract class Sie5DtoBase extends LogLevel implements Sie5Interface, Sie5XMLAttributesInterface
{
    /**
     * @var string
     */
    protected static $FMTERR1   = 'Unknown %s type #%s \'%s\'';
    protected static $FMTERR3   = '%s type \'%s\' requires 2+';
    protected static $FMTERR4   = '%s type #%s \'%s\' requires 2+';
    protected static $FMTERR11  = '%s::%s (%s) is not unique';
    protected static $FMTERR12  = '%s::%s (%s-%s) is not unique';
    protected static $FMTERR5   = '%s Imparity error key \'%s\' and type \'%s\'';
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
        foreach( RenderFactory::getInstancePropValues( $sie5DtoBase ) as $propertyValue ) {
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
        return RenderFactory::dispObject( $this );
    }

    /**
     * Return rendered error message
     *
     * @param string    $classFqcn fqcn
     * @param string    $propName
     * @return string
     */
    protected static function errMissing( string $classFqcn, string $propName ) : string
    {
        static $FMT = ' is required';
        return self::getClassPropStr( $classFqcn, $propName ) . $FMT ;
    }

    /**
     * @param string $classFqcn
     * @param string $propName
     * @return string
     */
    protected static function getClassPropStr( string $classFqcn, string $propName ) : string
    {
        static $FMT = '%s::%s';
        static $BS  = '\\';
        return sprintf(
            $FMT,
            substr( $classFqcn, ( strrpos( $classFqcn, $BS )) + 1 ),
            $propName
        );
    }
}
