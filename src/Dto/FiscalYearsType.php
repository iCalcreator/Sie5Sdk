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
namespace Kigkonsult\Sie5Sdk\Dto;

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\SortFactory;
use TypeError;

use function array_keys;
use function sprintf;
use function usort;

class FiscalYearsType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var FiscalYearType[]
     *
     * Declaration of a fiscal years
     */
    private array $fiscalYear = [];

    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local  = [];
        $inside = [];
        foreach( array_keys( $this->fiscalYear ) as $ix ) { // element ix
            $inside[$ix] = [];
            if( $this->fiscalYear[$ix]->isValid( $inside[$ix] )) {
                unset( $inside[$ix] );
            }
        } // end foreach
        if( ! empty( $inside )) {
            $key         = self::getClassPropStr( self::class, self::FISCALYEAR );
            $local[$key] = $inside;
        } // end if
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single FiscalYearType
     *
     * @param FiscalYearType $fiscalYear
     * @return static
     * @throws InvalidArgumentException
     */
    public function addFiscalYear( FiscalYearType $fiscalYear ) : self
    {
        if( $fiscalYear->isValid() &&
            (
                true !== $this->isFiscalYearStartEndUnique(
                    $fiscalYear->getStart(),
                    $fiscalYear->getEnd()
                )
            )
        ) {
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
    public function getFiscalYear() : array
    {
        return $this->fiscalYear;
    }

    /**
     * Return bool true if start/ends NOT overleap existing ones
     *
     * @param string $newStart
     * @param string $newEnd
     * @return bool     true if unique, false if not
     */
    public function isFiscalYearStartEndUnique( string $newStart, string $newEnd ) : bool
    {
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
     * Set FiscalYearTypes, array
     *
     * @param FiscalYearType[]  $fiscalYears
     * @return static
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function setFiscalYear( array $fiscalYears ) : self
    {
        foreach( $fiscalYears as $fiscalYear ) {
            $this->addFiscalYear( $fiscalYear );
        } // end foreach
        return $this;
    }
}
