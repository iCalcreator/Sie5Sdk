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

use TypeError;

use function array_keys;

class SuppliersType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var SupplierType[]
     */
    private array $supplier = [];

    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( ! empty( $this->supplier )) {
            $inside = [];
            foreach( array_keys( $this->supplier ) as $ix ) {
                $inside[$ix] = [];
                if( $this->supplier[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::SUPPLIER );
                $local[$key] = $inside;
            } // end if
        } // end if
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single SupplierType
     *
     * @param SupplierType $supplier
     * @return static
     */
    public function addSupplier( SupplierType $supplier ) : self
    {
        $this->supplier[] = $supplier;
        return $this;
    }

    /**
     * @return SupplierType[]
     */
    public function getSupplier() : array
    {
        return $this->supplier;
    }

    /**
     * Set SupplierTypes, array
     *
     * @param SupplierType[] $supplier
     * @return static
     * @throws TypeError
     */
    public function setSupplier( array $supplier ) : self
    {
        foreach( $supplier as $value ) {
            $this->addSupplier( $value );
        } // end foreach
        return $this;
    }
}
