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

use function sprintf;
use function strpos;

class SoftwareProductType  extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var string
     * @static
     */
    private static $FMT = '%s (%s)';

    /**
     * @var string
     *
     * Attribute name="name" use="required"
     * Name of the software that has created the file
     */
    private $name = self::PRODUCTNAME;

    /**
     * @var string
     *
     * Attribute name="version" use="required"
     * Version of the software that has created the file
     */
    private $version = self::PRODUCTVERSION;

    /**
     * Factory method, set name and version
     *
     * @param string $name
     * @param string $version
     * @return static
     */
    public static function factoryNameVersion( string $name, string $version ) : self
    {
        return self::factory()
            ->setName( $name )
            ->setVersion( $version );
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null ) : bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SoftwareProductType
     */
    public function setName( string $name ) : self
    {
        $this->name = ( false !== strpos( $name, self::PRODUCTNAME ))
            ? $name
            : sprintf( self::$FMT, $name, self::PRODUCTNAME );
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion() : string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return SoftwareProductType
     */
    public function setVersion( string $version ) : self
    {
        $this->version = ( false !== strpos( $version, self::PRODUCTVERSION ))
            ? $version
            : sprintf( self::$FMT, $version, self::PRODUCTVERSION );
        return $this;
    }
}
