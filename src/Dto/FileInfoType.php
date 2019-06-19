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

class FileInfoType extends Sie5DtoExtAttrBase
{

    /**
     * @var SoftwareProductType
     *                         Name of the software that has created the file
     * @acces private
     */
    private $softwareProduct = null;

    /**
     * @var FileCreationType
     * @acces private
     */
    private $fileCreation = null;

    /**
     * @var CompanyType
     *                 General information about the company (or other organization)
     *                 whose fiscal data is represented in the file
     * @acces private
     */
    private $company = null;

    /**
     * @var FiscalYearsType
     *                     Container for fiscal years
     * @acces private
     */
    private $fiscalYears = null;

    /**
     * @var AccountingCurrencyType
     * @acces private
     */
    private $accountingCurrency = null;

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = $inside = [];
        if( empty( $this->softwareProduct )) {
            $local[self::SOFTWAREPRODUCT] = false;
        }
        elseif( ! $this->softwareProduct->isValid( $inside )) {
            $local[self::SOFTWAREPRODUCT] = $inside;
            $inside = [];
        }
        if( empty( $this->fileCreation )) {
            $local[self::FILECREATION] = false;
        }
        elseif( ! $this->fileCreation->isValid( $inside )) {
            $local[self::FILECREATION] = $inside;
            $inside = [];
        }
        if( empty( $this->company )) {
            $local[self::COMPANY] = false;
        }
        elseif( ! $this->company->isValid( $inside )) {
            $local[self::COMPANY] = $inside;
            $inside = [];
        }
        if( empty( $this->fiscalYears )) {
            $local[self::FISCALYEARS] = false;
        }
        elseif( ! $this->fiscalYears->isValid( $inside )) {
            $local[self::FISCALYEARS] = $inside;
            $inside = [];
        }
        if( empty( $this->accountingCurrency )) {
            $local[self::ACCOUNTINGCURRENCY] = false;
        }
        elseif( ! $this->accountingCurrency->isValid( $inside )) {
            $local[self::ACCOUNTINGCURRENCY] = $inside;
            $inside = [];
        }
        if( ! empty( $local )) {
            $expected[self::FILEINFO] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return SoftwareProductType
     */
    public function getSoftwareProduct() {
        return $this->softwareProduct;
    }

    /**
     * @param SoftwareProductType $softwareProduct
     * @return static
     */
    public function setSoftwareProduct( SoftwareProductType $softwareProduct ) {
        $this->softwareProduct = $softwareProduct;
        return $this;
    }

    /**
     * @return FileCreationType
     */
    public function getFileCreation() {
        return $this->fileCreation;
    }

    /**
     * @param FileCreationType $fileCreation
     * @return static
     */
    public function setFileCreation( FileCreationType $fileCreation ) {
        $this->fileCreation = $fileCreation;
        return $this;
    }

    /**
     * @return CompanyType
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * @param CompanyType $company
     * @return static
     */
    public function setCompany( CompanyType $company ) {
        $this->company = $company;
        return $this;
    }

    /**
     * @return FiscalYearsType
     */
    public function getFiscalYears() {
        return $this->fiscalYears;
    }

    /**
     * @param FiscalYearsType $fiscalYears
     * @return static
     */
    public function setFiscalYears( FiscalYearsType $fiscalYears ) {
        $this->fiscalYears = $fiscalYears;
        return $this;
    }

    /**
     * @return AccountingCurrencyType
     */
    public function getAccountingCurrency() {
        return $this->accountingCurrency;
    }

    /**
     * @param AccountingCurrencyType $accountingCurrency
     * @return static
     */
    public function setAccountingCurrency( AccountingCurrencyType $accountingCurrency ) {
        $this->accountingCurrency = $accountingCurrency;
        return $this;
    }

}