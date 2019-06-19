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
namespace Kigkonsult\Sie5Sdk\Dto;

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

use function sprintf;
use function strpos;

class SoftwareProductType  extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var string
     * @access private
     * @static
     */
    private static $FMT = '%s (%s)';

    /**
     * @var string
     *
     * attribute name="name" use="required"
     * Name of the software that has created the file
     *
     * @access private
     */
    private $name = self::PRODUCTNAME;

    /**
     * @var string
     *
     * attribute name="version" use="required"
     * Version of the software that has created the file
     */
    private $version = self::PRODUCTVERSION;



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        return true;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SoftwareProductType
     * @throws InvalidArgumentException
     */
    public function setName( $name ) {
        $name = CommonFactory::assertString( $name );
        $this->name = ( false !== strpos( $name, self::PRODUCTNAME ))
            ? $name
            : sprintf( self::$FMT, $name, self::PRODUCTNAME );
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @param string $version
     * @return SoftwareProductType
     * @throws InvalidArgumentException
     */
    public function setVersion( $version ) {
        $version = CommonFactory::assertString( $version );
        $this->version = ( false !== strpos( $version, self::PRODUCTVERSION ))
            ? $version
            : sprintf( self::$FMT, $version, self::PRODUCTVERSION );
        return $this;
    }

}