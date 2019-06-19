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
namespace Kigkonsult\Sie5Sdk;

use Katzgrau\KLogger\Logger as KLogger;
use Kigkonsult\LoggerDepot\LoggerDepot;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

abstract class BaseTest extends TestCase
{

    protected static $CMPERR1 = 'Failed asserting that two Sie (%s) documents are equal.';
    protected static $FMTTIME = '%s %s : %01.6f%s';

    public static $SieXMLAttributes = [
        'xmlns:xsi'          => "http://www.w3.org/2001/XMLSchema-instance",
        'xmlns:xsd'          => "http://www.w3.org/2001/XMLSchema",
        'xsi:schemaLocation' => "http://www.sie.se/sie5 http://www.sie.se/sie5.xsd",
        'xmlns'              => "http://www.sie.se/sie5"
    ];

    public static $DsigXMLAttributes = [
        'xmlns' => "http://www.w3.org/2000/09/xmldsig#"
    ];

    public static function getCm( $name ) {
        return substr( $name, ( strrpos($name,  '\\' ) + 1 ));
    }

    public static function getBasePath() {
        $dir0 = $dir = __DIR__;
        $level = 6;
        while( ! is_dir( $dir . DIRECTORY_SEPARATOR . 'test' )) {
            $dir = realpath( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR );
            if( false == $dir ) {
                $dir = $dir0;
                break;
            }
            $level -= 1;
            if( empty( $level )) {
                $dir = $dir0;
                break;
            }
        }
        return $dir . DIRECTORY_SEPARATOR;
    }

    public static function setUpBeforeClass() {
        if( defined( 'LOG' ) && ( false !== LOG )) {
            $basePath = self::getBasePath() . LOG . DIRECTORY_SEPARATOR;
            $fileName = self::getCm( get_called_class()) . '.log';
            file_put_contents( $basePath . $fileName, '' );
            $logger   = new KLogger(
                $basePath,
                LogLevel::DEBUG,
                [ 'filename' => $fileName ]
            );
        }
        else {
            $logger = new NullLogger();
        }
        $key = __NAMESPACE__;
        if( ! LoggerDepot::isLoggerSet( $key )) {
            LoggerDepot::registerLogger( $key, $logger );
        }
    }

    public static function tearDownAfterClass() {
        foreach( LoggerDepot::getLoggerKeys() as $key ) {
            LoggerDepot::unregisterLogger( $key );
        }
    }

}