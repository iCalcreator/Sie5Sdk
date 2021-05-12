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

class FileInfoTypeEntry extends Sie5DtoExtAttrBase
{
    /**
     * @var SoftwareProductType
     *
     * Name of the software that has created the file
     */
    private $softwareProduct = null;

    /**
     * @var FileCreationType
     */
    private $fileCreation = null;

    /**
     * @var CompanyTypeEntry
     *
     * General information about the company (or other organization)
     * whose fiscal data is represented in the file
     */
    private $company = null;

    /**
     * @var AccountingCurrencyType
     *
     * Attribute  minOccurs="0"
     */
    private $accountingCurrency = null;

//  use ExtensionAttributeTrait;

    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) : bool
    {
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
        if( ! empty( $this->accountingCurrency ) && ! $this->accountingCurrency->isValid( $inside )) {
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
     * @return null|SoftwareProductType
     */
    public function getSoftwareProduct()
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
    public function getFileCreation()
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
    public function getCompany()
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
    public function getAccountingCurrency()
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
