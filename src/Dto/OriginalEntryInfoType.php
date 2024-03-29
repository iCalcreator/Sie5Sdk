<?php
/**
 * Sie5Sdk    PHP SDK for Sie5 export/import format
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
namespace Kigkonsult\Sie5Sdk\Dto;

use DateTime;

class OriginalEntryInfoType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var DateTime|null
     *
     * Attribute name="date" type="xsd:date" use="required"
     */
    private ?DateTime $date;

    /**
     * @var string|null
     *
     * Attribute name="by" type="xsd:string" use="required"
     * Name of the person, routine or system who/which enterded the record.
     */
    private ?string $by = null;

    /**
     * Factory method, set by/date (both required)
     *
     * @param string $by
     * @param DateTime $date
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
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( empty( $this->date )) {
            $local[] = self::errMissing(self::class, self::DATE );
        }
        if( null === $this->by ) {
            $local[] = self::errMissing(self::class, self::BY );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return DateTime
     */
    public function getDate() : DateTime
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
     * @return string
     */
    public function getBy() : string
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
