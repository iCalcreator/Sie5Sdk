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
namespace Kigkonsult\Sie5Sdk\XMLParse;

use Kigkonsult\LoggerDepot\LoggerDepot;
use Kigkonsult\Sie5Sdk\Sie5Interface;
use Kigkonsult\Sie5Sdk\Sie5XMLAttributesInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use XMLReader;

use function get_called_class;

abstract class Sie5ParserBase extends LogLevel implements Sie5Interface, Sie5XMLAttributesInterface
{
    /**
     * @var string
     * @static
     */
    protected static $FMTERRDATE = 'Error parsing %s';
    /**
     * @var mixed
     */
    protected $logger = null;

    /**
     * @var string
     * @static
     */
    protected static $FMTstartNode    = '%s Start (%s) %s';
    protected static $FMTattrFound    = '%s attribute %s = %s';
    protected static $FMTextAttrSaved = 'save extensionAttributes %s';
    protected static $GLUE            = ',';
    protected static $FMTreadNode     = '%s Found (%s) %s';

    /**
     * @var array $nodeTypes
     * @static
     */
    protected static $nodeTypes = [
        0  => 'NONE',
        1  => 'ELEMENT',
        2  => 'ATTRIBUTE',
        3  => 'TEXT',
        4  => 'CDATA',
        5  => 'ENTITY_REF',
        6  => 'ENTITY',
        7  => 'PI',
        8  => 'COMMENT',
        9  => 'DOC',
        10 => 'DOC_TYPE',
        11 => 'DOC_FRAGMENT',
        12 => 'NOTATION',
        13 => 'WHITESPACE',
        14 => 'SIGNIFICANT_WHITESPACE',
        15 => 'END_ELEMENT',
        16 => 'END_ENTITY',
        17 => 'XML_DECLARATION',
    ];


    /**
     * @var XMLReader
     */
    protected $reader = null;

    /**
     * Constructor
     *
     * @param XMLReader $reader
     */
    public function __construct( $reader = null )
    {
        $this->logger = LoggerDepot::getLogger( __CLASS__ );
        if( empty( $this->logger )) {
            $this->logger = new NullLogger();
        }
        if( ! empty( $reader )) {
            $this->reader = $reader;
        }
    }

    /**
     * Factory
     *
     * @param XMLReader $reader
     * @return static
     * @static
     */
    public static function factory( $reader = null  ) : self
    {
        $class = get_called_class();
        return new $class( $reader );
    }
}
