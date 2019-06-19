<?php

namespace Kigkonsult\Sie5Sdk\Dto;

use Kigkonsult\LoggerDepot\LoggerDepot;
use Kigkonsult\Sie5Sdk\Sie5Interface;
use Kigkonsult\Sie5Sdk\Sie5XMLAttributesInterface;
use Psr\Log\LogLevel;
use XMLReader;

abstract class Sie5Base extends LogLevel implements Sie5Interface, Sie5XMLAttributesInterface
{

    /**
     * @var mixed
     * @access protected
     */
    protected $logger = null;

    /**
     * @var array
     * @access protected
     */
    protected $XMLattributes = [];

    /**
     * Class constructor
     */
    public function __construct() {
        $this->logger = LoggerDepot::getLogger( __CLASS__ );
    }

    /**
     * Class factory
     *
     * @return static
     * @access static
     */
    public static function factory() {
        $class = get_called_class();
        return new $class();
    }

    /**
     * Get XML attributes
     *
     * @return array
     */
    public function getXMLattributes() {
        return $this->XMLattributes;
    }

    /**
     * Set XML attributes, opt propagate down
     *
     * @param string $key
     * @param string $value
     * @param bool   $propagateDown
     * @return static
     */
    public function setXMLattribute( $key, $value, $propagateDown = false ) {
        $this->XMLattributes[$key] = $value;
        if( $propagateDown ) {
            self::propagateDown( $this, $key, $value, false );
        }
        return $this;
    }

    /**
     * Unset XML attribute, opt down
     *
     * @param string $key
     * @param bool   $propagateDown
     * @return static
     */
    public function unsetXMLattribute( $key, $propagateDown = false ) {
        unset( $this->XMLattributes[$key] );
        if( $propagateDown ) {
            self::propagateDown( $this, $key, null, true );
        }
        return $this;
    }

    /**
     * Propagate set or remove XML attribute down
     *
     * @param Sie5Base $sie5Base
     * @param string   $key
     * @param string   $value
     * @param bool     $unset
     * @access protected
     * @tatic
     */
    protected static function propagateDown( Sie5Base $sie5Base, $key, $value, $unset = false ) {
        if( $unset ) {
            unset( $sie5Base->XMLattributes[$key] );
        }
        foreach( get_object_vars( $sie5Base ) as $propertyValue ) {
            if( $propertyValue instanceof Sie5Base ) {
                if( $unset ) {
                    $propertyValue->unsetXMLattribute( $key, true );
                }
                else {
                    $propertyValue->setXMLattribute( $key, $value, true );
                }
            } // end if
            elseif( is_array( $propertyValue )) {
                self::propagateDownArray( $propertyValue, $key, $value, $unset );
            } // end elseif
        } // end foreach
    }

    /**
     * Propagate set or remove XML attribute down in array
     *
     * @param array  $arrayValue
     * @param string $key
     * @param string $value
     * @param bool   $unset
     * @access protected
     * @tatic
     */
    protected static function propagateDownArray( array $arrayValue, $key, $value, $unset = false ) {
        foreach( $arrayValue as $arrayValue2 ) {
            if( $arrayValue2 instanceof Sie5DtoInterface ) {
                if( $unset ) {
                    $arrayValue2->unsetXMLattribute( $key, true );
                }
                else {
                    $arrayValue2->setXMLattribute( $key, $value, true );
                }
            } // end if
            elseif( is_array( $arrayValue2 )) {
                self::propagateDownArray( $arrayValue2, $key, $value, $unset );
            } // end elseif
        } // end foreach
    }


    /**
     * Set XML attributes
     *
     * @param XMLReader $reader Element node
     * @return static
     */
    public function setXMLattributes( $reader ) {
        $this->XMLattributes[self::BASEURI]      = $reader->baseURI ;
        $this->XMLattributes[self::LOCALNAME]    = $reader->localName ;
        $this->XMLattributes[self::NAME]         = $reader->name ;
        $this->XMLattributes[self::NAMESPACEURI] = $reader->namespaceURI ;
        $this->XMLattributes[self::PREFIX]       = $reader->prefix ;
        return $this;
    }

    /**
     * Return string
     *
     * @return string
     */
    public function toString() {
        return Sie5Base::dispObject( $this );
    }

    /**
     * Return string
     *
     * @param Sie5Base $instance
     * @return string
     * @access protected
     */
    protected static function dispObject( Sie5Base $instance ) {
        $class   = get_class( $instance );
        $string  = 'start ' . $class . ' -----v' . PHP_EOL;
        $string .= 'XMLattributes : ' .
            str_replace( [PHP_EOL, ' '], null, var_export( $instance->getXMLattributes(), true )) .
            PHP_EOL;
        foreach( $instance as $property => $value ) {
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
                    $string .= Sie5Base::dispArray( $property, $value );
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
     * @access protected
     */
    protected static function dispArray( $property, array $value ) {
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