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

use DateTime;

class LockingInfoType extends Sie5DtoBase implements Sie5DtoInterface, LedgerEntryTypesInterface
{
    /**
     * @var DateTime
     *
     * Attribute name="date" type="xsd:date" use="required"
     */
    private $date = null;

    /**
     * @var string
     *
     * Attribute name="by" type="xsd:string" use="required"
     * Name of the person, routine or system who/which locked the record.
     */
    private $by = null;

    /**
     * Factory method, set by/date (both required)
     *
     * @param string    $by
     * @param DateTime  $date
     * @return static
     */
    public static function factoryByDate( string $by, DateTime $date ) : self
    {
        return self::factory()
            ->setBy( $by )
            ->setDate( $date );
    }

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        parent:: __construct();
        $this->date = new DateTime();
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null ) : bool
    {
        $local = [];
        if( empty( $this->date )) {
            $local[] = self::errMissing(self::class, self::DATE );
        }
        if( empty( $this->by )) {
            $local[] = self::errMissing(self::class, self::BY );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return null|DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return static
     */
    public function setDate( DateTime $date ) : self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getBy()
    {
        return $this->by;
    }

    /**
     * @param string $by
     * @return static
     */
    public function setBy( string $by ) : self
    {
        $this->by = $by;
        return $this;
    }
}
