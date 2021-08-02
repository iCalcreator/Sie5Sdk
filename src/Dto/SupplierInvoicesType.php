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

use TypeError;

use function array_keys;

class SupplierInvoicesType extends BaseSubdividedAccountType
{
    /**
     * @var array SupplierInvoiceType[]
     */
    private $supplierInvoice = [];

    /**
     * Return bool true is instance is valid
     *
     * @param array $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null ) : bool
    {
        $local = [];
        if( null === $this->primaryAccountId ) {
            $local[] = self::errMissing(self::class, self::PRIMARYACCOUNTID );
        }
        if( ! empty( $this->supplierInvoice )) {
            $inside = [];
            foreach( array_keys( $this->supplierInvoice ) as $ix ) {
                $inside[$ix] = [];
                if( $this->supplierInvoice[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::SUPPLIERINVOICE );
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
     * Add single SupplierInvoiceType
     *
     * @param SupplierInvoiceType $supplierInvoice
     * @return static
     */
    public function addSupplierInvoice( SupplierInvoiceType $supplierInvoice ) : self
    {
        $this->supplierInvoice[] = $supplierInvoice;
        return $this;
    }

    /**
     * @return SupplierInvoiceType[]
     */
    public function getSupplierInvoice() : array
    {
        return $this->supplierInvoice;
    }

    /**
     * Return array SupplierIds
     *
     * @return array
     */
    public function getAllSupplierInvoiceSupplierIds() : array
    {
        $supplierIds = [];
        foreach( array_keys( $this->supplierInvoice ) as $ix ) {
            $supplierIds[] = $this->supplierInvoice[$ix]->getSupplierId();
        } // end foreach
        sort( $supplierIds );
        return $supplierIds;
    }

    /**
     * SEt SupplierInvoiceTypes, array
     *
     * @param SupplierInvoiceType[] $supplierInvoice
     * @return static
     * @throws TypeError
     */
    public function setSupplierInvoice( array $supplierInvoice ) : self
    {
        foreach( $supplierInvoice as $value ) {
            $this->addSupplierInvoice( $value );
        } // end foreach
        return $this;
    }
}
