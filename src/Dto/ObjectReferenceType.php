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

class ObjectReferenceType
    extends Sie5DtoBase
    implements BaseBalanceTypesInterface, LedgerEntryTypesInterface, LedgerEntryTypeEntriesInterface
{

    /**
     * @var int
     *         attribute name="dimId" type="xsd:positiveInteger" use="required"
     *         Dimension identifier.
     *         Must correspond to a dimension specified under Dimensions
     * @access private
     */
    private $dimId = null;

    /**
     * @var string
     *            attribute name="objectId" type="xsd:string" use="required"
     *            Object identifier
     * @access private
     */
    private $objectId = null;



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->dimId )) {
            $local[self::DIMID] = false;
        }
        if( empty( $this->objectId )) {
            $local[self::OBJECTID] = false;
        }
        if( ! empty( $local )) {
            $expected[self::OBJECTREFERENCE] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return int
     */
    public function getDimId() {
        return $this->dimId;
    }

    /**
     * @param int $dimId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setDimId( $dimId ) {
        $this->dimId = CommonFactory::assertPositiveInteger( $dimId );
        return $this;
    }

    /**
     * @return string
     */
    public function getObjectId() {
        return $this->objectId;
    }

    /**
     * @param string $objectId
     * @return static
     * @throws InvalidArgumentException
     */
    public function setObjectId( $objectId ) {
        $this->objectId = CommonFactory::assertString( $objectId );
        return $this;
    }

}