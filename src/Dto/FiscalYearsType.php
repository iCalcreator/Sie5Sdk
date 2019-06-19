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

use function array_keys;
use function gettype;
use Kigkonsult\Sie5Sdk\Impl\SortFactory;
use function sprintf;
use function usort;

class FiscalYearsType extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var array   [ *FiscalYearType ]
     *
     * Declaration of a fiscal years
     * @access private
     */
    private $fiscalYear = [];



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        foreach( array_keys( $this->fiscalYear ) as $ix1 ) { // element ix
            $inside = [];
            if( ! $this->fiscalYear[$ix1]->isValid( $inside )) {
                $local[$ix1] = $inside;
            }
        }
        if( ! empty( $local )) {
            $expected[self::FISCALYEARS] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param FiscalYearType $fiscalYear
     * @return static
     * @throws InvalidArgumentException
     */
    public function addFiscalYear( FiscalYearType $fiscalYear ) {
        if( ! $this->isFiscalYearStartEndUnique( $fiscalYear->getStart(), $fiscalYear->getEnd())) {
            throw new InvalidArgumentException(
                sprintf(
                    self::$FMTERR12,
                    self::FISCALYEARS,
                    self::FISCALYEAR,
                    $fiscalYear->getStart(),
                    $fiscalYear->getEnd()
                )
            );
        }
        $this->fiscalYear[] = $fiscalYear;
        usort( $this->fiscalYear, SortFactory::$fiscalYearTypeSorter );
        return $this;
    }

    /**
     * @return array
     */
    public function getFiscalYear() {
        return $this->fiscalYear;
    }

    /**
     * Return bool true if start/ends NOT overleap existing ones
     *
     * @param string $newStart
     * @param string $newEnd
     * @return bool     true if unique, false if not
     */
    public function isFiscalYearStartEndUnique( $newStart, $newEnd ) {
        foreach( array_keys( $this->fiscalYear ) as $ix ) {
            if((( $newStart < $this->fiscalYear[$ix]->getStart()) &&
                    ( $newEnd <= $this->fiscalYear[$ix]->getStart())) ||
                (( $newEnd > $this->fiscalYear[$ix]->getEnd()) &&
                    ( $newStart >= $this->fiscalYear[$ix]->getEnd()))) {
                continue;
            }
            return false;
        } // end foreach
        return true;
    }

    /**
     * @param array $fiscalYears   *FiscalYearType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setFiscalYear( array $fiscalYears ) {
        foreach( $fiscalYears as $ix => $fiscalYear ) {
            switch( true ) {
                case ( ! $fiscalYear instanceof FiscalYearType ) :
                    $type = gettype( $fiscalYear );
                    if( self::$OBJECT == $type ) {
                        $type = get_class( $fiscalYear );
                    }
                    throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::FISCALYEAR, $ix, $type ));
                    break;
                case ( ! $this->isFiscalYearStartEndUnique( $fiscalYear->getStart(), $fiscalYear->getEnd())) :
                    throw new InvalidArgumentException(
                        sprintf( self::$FMTERR12,
                            self::FISCALYEARS,
                            self::FISCALYEAR,
                            $ix,
                            $fiscalYear->getStart(),
                            $fiscalYear->getEnd())
                    );
                    break;
                default :
                    $this->fiscalYear[$ix] = $fiscalYear;
                    break;
            } // end switch
        } // end foreach
        usort( $this->fiscalYear, SortFactory::$fiscalYearTypeSorter );
        return $this;
    }

}