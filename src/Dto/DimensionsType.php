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

use InvalidArgumentException;

use function array_keys;
use function array_search;
use function get_class;
use function gettype;
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
    private $dimension = [];

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null) : bool
    {
        $local = [];
        foreach (array_keys($this->dimension) as $ix1) { // element ix
            $inside = [];
            if ( ! $this->dimension[$ix1]->isValid($inside)) {
                $local[self::DIMENSION][$ix1] = $inside;
            }
        } // end foreach
        if ( ! empty($local)) {
            $expected[self::DIMENSIONS] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param DimensionType $dimension
     * @return static
     * @throws InvalidArgumentException
     */
    public function addDimension( DimensionType $dimension ) : self
    {
        if (true !== $this->isDimensionsIdUnique($dimension->getId())) {
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
     * @param int $id
     * @return array|DimensionType|bool   if id given DimensionType, bool false on not found otherwise array
     */
    public function getDimension( int $id = null )
    {
        if ( ! is_null($id)) {
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
    public function isDimensionsIdUnique( int $id )
    {
        $hitIx = array_search( $id, $this->getAllDimensionIds());
        return ( false !== $hitIx ) ? $hitIx : true;
    }

    /**
     * @param array $dimensions [ *DimensionType ]
     * @return static
     * @throws InvalidArgumentException
     */
    public function setDimension( array $dimensions ) : self
    {
        foreach ($dimensions as $ix => $dimension) {
            switch (true) {
                case ( ! $dimension instanceof DimensionType) :
                    $type = gettype($dimension);
                    if (self::$OBJECT == $type) {
                        $type = get_class($dimension);
                    }
                    throw new InvalidArgumentException(sprintf(self::$FMTERR1, self::DIMENSION, $ix, $type));
                    break;
                case (true !== $this->isDimensionsIdUnique($dimension->getId())) :
                    throw new InvalidArgumentException(
                        sprintf(self::$FMTERR111, self::DIMENSION, self::ID, $ix, $dimension->getId())
                    );
                    break;
                default :
                    $this->dimension[$ix] = $dimension;
                    break;
            } // end switch
        } // end foreach
        return $this;
    }
}
