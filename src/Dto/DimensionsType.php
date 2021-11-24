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

use InvalidArgumentException;
use TypeError;

use function array_keys;
use function array_search;
use function is_null;
use function sprintf;
use function asort;

class DimensionsType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var DimensionType[]
     *
     * Container element for individual dimensions
     */
    private array $dimension = [];

    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null) : bool
    {
        $local  = [];
        $inside = [];
        foreach( array_keys($this->dimension) as $ix)  { // element ix
            $inside[$ix] = [];
            if ( $this->dimension[$ix]->isValid($inside[$ix] )) {
                unset( $inside[$ix] );
            }
        } // end foreach
        if( ! empty( $inside )) {
            $key         = self::getClassPropStr( self::class, self::DIMENSION );
            $local[$key] = $inside;
        } // end if
        if ( ! empty($local)) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single DimensionType
     *
     * @param DimensionType $dimension
     * @return static
     * @throws InvalidArgumentException
     */
    public function addDimension( DimensionType $dimension ) : self
    {
        if( $dimension->isValid() &&
            ( true !== $this->isDimensionsIdUnique( $dimension->getId()))) {
            throw new InvalidArgumentException(
                sprintf(self::$FMTERR11, self::DIMENSION, self::ID, $dimension->getId())
            );
        }
        $this->dimension[] = $dimension;
        return $this;
    }

    /**
     * Return DimensionType if (DimensionType-)id given, (bool false on not found) otherwise array
     *
     * @param null|int $id
     * @return array|DimensionType|bool   if id given DimensionType, bool false on not found otherwise array
     */
    public function getDimension( ? int $id = null ) : array | bool | DimensionType
    {
        if ( ! is_null( $id )) {
            $ix = $this->isDimensionsIdUnique( $id );
            return (true !== $ix) ? $this->dimension[$ix] : false;
        }
        return $this->dimension;
    }

    /**
     * Return array DimensionIds
     *
     * @return array
     */
    public function getAllDimensionIds() : array
    {
        $dimensionIds = [];
        foreach (array_keys($this->dimension) as $ix) {
            $dimensionIds[$ix] = $this->dimension[$ix]->getId();
        } // end foreach
        asort($dimensionIds);
        return $dimensionIds;
    }

    /**
     * Return int index if DimensionType id is set or bool true
     *
     * @param int $id
     * @return int|bool  DimensionType index or bool true if not found i.e. unique
     */
    public function isDimensionsIdUnique( int $id ) : bool | int
    {
        $hitIx = array_search( $id, $this->getAllDimensionIds(), true );
        return ( false !== $hitIx ) ? $hitIx : true;
    }

    /**
     * Set DimensionTypes, array
     *
     * @param array $dimensions [ *DimensionType ]
     * @return static
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function setDimension( array $dimensions ) : self
    {
        foreach( $dimensions as $dimension) {
            $this->addDimension( $dimension );
        } // end foreach
        return $this;
    }
}
