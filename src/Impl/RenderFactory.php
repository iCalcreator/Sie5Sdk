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
namespace Kigkonsult\Sie5Sdk\Impl;

use DateTime;
use Exception;
use Kigkonsult\Sie5Sdk\Dto\Sie5DtoBase;
use RuntimeException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

use function get_class;
use function is_array;
use function is_null;
use function is_object;
use function is_scalar;
use function method_exists;
use function str_replace;
use function sprintf;
use function var_export;

class RenderFactory
{
    /**
     * @param Sie5DtoBase $instance
     * @return array
     * @throws ReflectionException
     */
    public static function getInstancePropValues( Sie5DtoBase $instance ) : array {
        $output          = [];
        $reflectionClass = new ReflectionClass( $instance );
        foreach( $reflectionClass->getProperties( ReflectionProperty::IS_PRIVATE )
                 as $reflectionProperty ) {
            if( $reflectionProperty->isStatic()) {
                continue;
            }
            $reflectionProperty->setAccessible( true );
            $output[$reflectionProperty->getName()] = $reflectionProperty->getValue( $instance );
        } // end foreach
        return $output;
    }

    private static $NULL   = ' <null> ';
    private static $SP1    = ' ';
    private static $SP1C   = ' : ';
    private static $YMDHIS = 'YmdHis';
    private static $TS     = 'toString';
    private static $Q2     = '??';

    /**
     * Return string
     *
     * @param Sie5DtoBase $instance
     * @return string
     * @throws RuntimeException
     */
    public static function dispObject( Sie5DtoBase $instance ) : string {
        static $START   = 'start %s -----v%s';
        static $SP0     = '';
        static $XATTRST = 'XMLattributes';
        static $END     = 'end %s -----^%s';
        $class  = get_class( $instance );
        $string = sprintf( $START, $class, PHP_EOL );
        $string .= $XATTRST . self::$SP1C .
            str_replace(
                [ PHP_EOL, self::$SP1 ],
                $SP0,
                var_export( $instance->getXMLattributes(), true )
            ) . PHP_EOL;
        try {
            $propArr = self::getInstancePropValues( $instance );
        }
        catch( Exception $e ) {
            throw new RuntimeException( $e->getMessage(), null, $e );
        }
        foreach( $propArr as $propName => $propValue ) {
            if( $XATTRST == $propName ) {
                continue;
            }
            switch( true ) {
                case ( is_null( $propValue )) :
                    $string .= $propName . self::$SP1C . self::$NULL . PHP_EOL;
                    break;
                case ( is_scalar( $propValue )) :
                    $string .= $propName . self::$SP1C . ( empty( $propValue ) ? $SP0 : $propValue ) . PHP_EOL;
                    break;
                case ( is_array( $propValue )) :
                    $string .= self::dispArray( $propName, $propValue );
                    break;
                case ( $propValue instanceof DateTime ) :
                    $string .= $propName . self::$SP1C . $propValue->format( self::$YMDHIS ) . PHP_EOL;
                    break;
                case ( is_object( $propValue )) :
                    $string .= $propName . self::$SP1C;
                    $string .= ( method_exists( $propValue, self::$TS ))
                        ? PHP_EOL . $propValue->toString()
                        : self::$Q2 . PHP_EOL;
                    break;
                default :
                    $string .= $propName . self::$SP1C . PHP_EOL . var_export( $propValue, true ) . PHP_EOL;
            } // end switch
        } // end foreach
        $string .= sprintf( $END, $class, PHP_EOL );
        return $string;
    }

    /**
     * Return string
     *
     * @param string $propName
     * @param array  $arrVal
     * @return string
     */
    private static function dispArray( string $propName, array $arrVal ) : string {
        static $SP0 = '';
        static $IB1 = '[';
        static $IB2 = '] : ';
        static $IB3 = ']';
        $string = $SP0;
        foreach( $arrVal as $key2 => $value2 ) {
            $string .= $propName . $IB1 . $key2;
            switch( true ) {
                case is_null( $value2 ) :
                    $string .= $IB2 . self::$SP1C . self::$NULL . PHP_EOL;
                    break;
                case is_scalar( $value2 ) :
                    $string .= $IB2 . self::$SP1C . var_export( $value2, true ) . PHP_EOL;
                    break;
                case is_array( $value2 ) :
                    $string .= $IB3 . PHP_EOL . self::$SP1 .
                        self::dispArray( $propName . $IB1 . $key2 . $IB3, $value2 );
                    break;
                case ( $value2 instanceof DateTime ) :
                    $string .= $IB2 . self::$SP1C . $value2->format( self::$YMDHIS ) . PHP_EOL;
                    break;
                case is_object( $value2 ) :
                    $string .= $IB2 . self::$SP1C . ( method_exists( $value2, self::$TS ))
                        ? PHP_EOL . $value2->toString()
                        : self::$Q2 . PHP_EOL;
                    break;
                default :
                    $string .= $IB2 . self::$SP1C . var_export( $value2, true ) . PHP_EOL;
                    break;
            } // end switch
        } // end foreach
        return $string;
    }
}
