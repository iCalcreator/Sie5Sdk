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

use DateTime;
use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

class LockingInfoType extends Sie5DtoBase implements Sie5DtoInterface, LedgerEntryTypesInterface
{

    /**
     * @var DateTime
     *            attribute name="date" type="xsd:date" use="required"
     * @access private
     */
    private $date = null;

    /**
     * @var string
     *            attribute name="by" type="xsd:string" use="required"
     *            Name of the person, routine or system who/which locked the record.
     * @access private
     */
    private $by = null;

    /**
     * Class constructor
     *
     */
    public function __construct() {
        parent::__Construct();
        $this->date = new DateTime();
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->date )) {
            $local[self::DATE] = false;
        }
        if( is_null( $this->by )) {
            $local[self::BY] = false;
        }
        if( ! empty( $local )) {
            $expected[self::LOCKINGINFO] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return static
     */
    public function setDate( DateTime $date ) {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getBy() {
        return $this->by;
    }

    /**
     * @param string $by
     * @return static
     * @throws InvalidArgumentException
     */
    public function setBy( $by ) {
        $this->by = CommonFactory::assertString( $by );
        return $this;
    }

}