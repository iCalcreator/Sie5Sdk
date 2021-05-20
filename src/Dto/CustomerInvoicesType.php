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

use TypeError;

use function array_keys;

class CustomerInvoicesType extends BaseSubdividedAccountType
{
    /**
     * @var CustomerInvoiceType[]
     */
    private $customerInvoice = [];

    /**
     * Return bool true is instance is valid
     *
     * @param array $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null ) : bool
    {
        $local = [];
        if( empty( $this->primaryAccountId )) {
            $local[] = self::errMissing(self::class, self::PRIMARYACCOUNTID );
        }
        $inside = [];
        foreach( array_keys( $this->customerInvoice ) as $ix ) { // element ix
            $inside[$ix] = [];
            if( $this->customerInvoice[$ix]->isValid( $inside[$ix] )) {
                unset( $inside[$ix] );
            }
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::CUSTOMERINVOICE );
                $local[$key] = $inside;
            } // end if
        } // end foreach
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single CustomerInvoiceType
     *
     * @param CustomerInvoiceType $customerInvoice
     * @return static
     */
    public function addCustomerInvoice( CustomerInvoiceType $customerInvoice ) : self
    {
        $this->customerInvoice[] = $customerInvoice;
        return $this;
    }

    /**
     * @return array
     */
    public function getCustomerInvoice() : array
    {
        return $this->customerInvoice;
    }

    /**
     * Return array CustomerIds
     *
     * @return array
     */
    public function getAllCustomerInvoiceCustomerIds() : array
    {
        $customerIds = [];
        foreach( array_keys( $this->customerInvoice ) as $ix ) {
            $customerIds[] = $this->customerInvoice[$ix]->getCustomerId();
        }
        sort( $customerIds );
        return $customerIds;
    }

    /**
     * Set CustomerInvoiceTypes, array
     *
     * @param CustomerInvoiceType[] $customerInvoice
     * @return CustomerInvoicesType
     * @throws TypeError
     */
    public function setCustomerInvoice( array $customerInvoice ) : self
    {
        foreach( $customerInvoice as $value ) {
            $this->addCustomerInvoice( $value );
        } // end foreach
        return $this;
    }
}
