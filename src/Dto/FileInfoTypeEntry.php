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

class FileInfoTypeEntry extends Sie5DtoExtAttrBase
{
    /**
     * @var SoftwareProductType|null
     *
     * Name of the software that has created the file
     */
    private ?SoftwareProductType $softwareProduct = null;

    /**
     * @var FileCreationType|null
     */
    private ?FileCreationType $fileCreation = null;

    /**
     * @var CompanyTypeEntry|null
     *
     * General information about the company (or other organization)
     * whose fiscal data is represented in the file
     */
    private ?CompanyTypeEntry $company = null;

    /**
     * @var AccountingCurrencyType|null
     *
     * Attribute  minOccurs="0"
     */
    private ?AccountingCurrencyType $accountingCurrency = null;

//  use ExtensionAttributeTrait;

    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = $inside = [];
        if( empty( $this->softwareProduct )) {
            $local[] = self::errMissing(self::class, self::SOFTWAREPRODUCT );
        }
        elseif( ! $this->softwareProduct->isValid( $inside )) {
            $local[] = $inside;
            $inside  = [];
        }
        if( empty( $this->fileCreation )) {
            $local[] = self::errMissing(self::class, self::FILECREATION );
        }
        elseif( ! $this->fileCreation->isValid( $inside )) {
            $local[] = $inside;
            $inside  = [];
        }
        if( empty( $this->company )) {
            $local[] = self::errMissing(self::class, self::COMPANY );
        }
        elseif( ! $this->company->isValid( $inside )) {
            $local[] = $inside;
            $inside  = [];
        }
        if( ! empty( $this->accountingCurrency ) &&
            ! $this->accountingCurrency->isValid( $inside )) {
            $local[] = $inside;
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return null|SoftwareProductType
     */
    public function getSoftwareProduct() : ?SoftwareProductType
    {
        return $this->softwareProduct;
    }

    /**
     * @param SoftwareProductType $softwareProduct
     * @return static
     */
    public function setSoftwareProduct( SoftwareProductType $softwareProduct ) : self
    {
        $this->softwareProduct = $softwareProduct;
        return $this;
    }

    /**
     * @return null|FileCreationType
     */
    public function getFileCreation() : ?FileCreationType
    {
        return $this->fileCreation;
    }

    /**
     * @param FileCreationType $fileCreation
     * @return static
     */
    public function setFileCreation( FileCreationType $fileCreation ) : self
    {
        $this->fileCreation = $fileCreation;
        return $this;
    }

    /**
     * @return null|CompanyTypeEntry
     */
    public function getCompany() : ?CompanyTypeEntry
    {
        return $this->company;
    }

    /**
     * @param CompanyTypeEntry $company
     * @return static
     */
    public function setCompany( CompanyTypeEntry $company ) : self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return null|AccountingCurrencyType
     */
    public function getAccountingCurrency() : ?AccountingCurrencyType
    {
        return $this->accountingCurrency;
    }

    /**
     * @param AccountingCurrencyType $accountingCurrency
     * @return static
     */
    public function setAccountingCurrency( AccountingCurrencyType $accountingCurrency ) : self
    {
        $this->accountingCurrency = $accountingCurrency;
        return $this;
    }
}
