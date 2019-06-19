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

class GeneralSubdividedAccountType extends BaseSubdividedAccountType
{

    /**
     * @var array  [ *GeneralObjectType ]
     * @access private
     */
    private $generalObject = [];



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
        foreach( array_keys( $this->generalObject ) as $ix1 ) { // element ix
            $inside = [];
            if( ! $this->generalObject[$ix1]->isValid( $inside )) {
                $local[self::GENERALOBEJCT][$ix1] = $inside;
            }
        }
        if( ! empty( $local )) {
            $expected[self::GENERALSUBDIVIDEDACCOUNT] = $local;
            return false;
        }
        return true;
    }

    /**
     * @param GeneralObjectType $generalObject
     * @return static
     */
    public function addGeneralObject( GeneralObjectType $generalObject ) {
        $this->generalObject[] = $generalObject;
        return $this;
    }

    /**
     * @return array
     */
    public function getGeneralObject() {
        return $this->generalObject;
    }

    /**
     * @param array $generalObject
     * @return static
     * @throws InvalidArgumentException
     */
    public function setGeneralObject( array $generalObject ) {
        foreach( $generalObject as $ix => $value ) {
            if( $value instanceof GeneralObjectType ) {
                $this->generalObject[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::GENERALOBEJCT, $ix, $type ));
            }
        }
        return $this;
    }

}