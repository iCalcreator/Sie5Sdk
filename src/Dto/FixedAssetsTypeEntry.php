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
use function sprintf;

class FixedAssetsTypeEntry extends BaseSubdividedAccountTypeEntry
{

    /**
     * @var array    [ *FixedAssetTypeEntry ]
     * @access private
     */
    private $fixedAsset = [];



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->primaryAccountId )) {
            $local[self::PRIMARYACCOUNTID] = false;
        }
        if( ! empty( $this->fixedAsset )) {
            foreach( array_keys( $this->fixedAsset ) as $ix1 ) { // element ix
                $inside = [];
                if( ! $this->fixedAsset[$ix1]->isValid( $inside ) ) {
                    $local[$ix1] = $inside;
                }
            }
        }
        if( ! empty( $local )) {
            $expected[self::FIXEDASSETS] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param FixedAssetTypeEntry $fixedAsset
     * @return static
     */
    public function addFixedAsset( FixedAssetTypeEntry $fixedAsset ) {
        $this->fixedAsset[] = $fixedAsset;
        return $this;
    }

    /**
     * @return array
     */
    public function getFixedAsset() {
        return $this->fixedAsset;
    }

    /**
     * @param array $fixedAsset
     * @return static
     * @throws InvalidArgumentException
     */
    public function setFixedAsset( array $fixedAsset ) {
        foreach( $fixedAsset as $ix => $value ) {
            if( $value instanceof FixedAssetTypeEntry ) {
                $this->fixedAsset[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::FIXEDASSET, $ix, $type ));
            }
        }
        return $this;
    }

}