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
use Kigkonsult\DsigSdk\Dto\SignatureType;

use function array_keys;
use function array_unique;
use function gettype;
use Kigkonsult\Sie5Sdk\Impl\SortFactory;
use function sort;
use function sprintf;
use function usort;

/**
 * Class Sie
 *
 * Root element for Sie
 */
class Sie extends Sie5DtoBase implements Sie5DtoInterface
{

    /**
     * @var FileInfoType
     *                  maxOccurs="1" minOccurs="1"
     *                  General information about the file
     * @access private
     */
    private $fileInfo = null;

    /**
     * @var AccountsType
     *                  Chart of accounts
     * @access private
     */
    private $accounts = null;


    /**
     * @var DimensionsType
     *                    minOccurs="0"
     *                    Container for dimensions
     * @access private
     */
    private $dimensions = null;

    /**
     * @var array  [ *CustomerInvoicesType ]
     *                        minOccurs="0" maxOccurs="unbounded"
     * @access private
     */
    private $customerInvoices = [];

    /**
     * @var array   [ *SupplierInvoicesType ]
     *                       minOccurs="0" maxOccurs="unbounded"
     * @access private
     */
    private $supplierInvoices = [];

    /**
     * @var array   [ *FixedAssetsType ]
     *                        minOccurs="0" maxOccurs="unbounded"
     * @access private
     */
    private $fixedAssets = [];

    /**
     * @var array   [ *GeneralSubdividedAccountType ]
     *                        minOccurs="0" maxOccurs="unbounded"
     * @access private
     */
    private $generalSubdividedAccount = [];

    /**
     * @var CustomersType
     *                   Container for customers
     *                   minOccurs="0"
     * @access private
     */
    private $customers = null;

    /**
     * @var SuppliersType
     *                   Container for suppliers
     *                   minOccurs="0"
     * @access private
     */
    private $suppliers = null;

    /**
     * @var AccountAggregationsType
     *                             minOccurs="0"
     * @access private
     */
    private $accountAggregations = null;

    /**
     * @var array    [ *JournalType ]
     *                       Container for individual journal
     *                       minOccurs="0" maxOccurs="unbounded"
     * @access private
     */
    private $journal = [];

    /**
     * @var DocumentsType
     *                   Container for documents
     *                   minOccurs="0"
     * @access private
     */
    private $documents = null;

    /**
     * @var SignatureType
     * @access private
     */
    private $signature = null;



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = $inside = [];
        if( empty( $this->fileInfo )) {
            $local[self::FILEINFO] = false;
        }
        elseif( ! $this->fileInfo->isValid( $inside )) {
            $local[self::FILEINFO] = $inside;
            $inside = [];
        }
        if( empty( $this->accounts )) {
            $local[self::ACCOUNTS] = false;
        }
        elseif( ! $this->accounts->isValid( $inside )) {
            $local[self::ACCOUNTS] = $inside;
            $inside = [];
        }
        if( ! empty( $this->dimensions ) && ! $this->dimensions->isValid( $inside )) {
            $local[self::DIMENSIONS] = $inside;
            $inside = [];
        }
        if( ! empty( $this->customerInvoices )) {
            foreach( array_keys( $this->customerInvoices ) as $ix ) {
                if( ! $this->customerInvoices[$ix]->isValid( $inside )) {
                    $local[self::CUSTOMERINVOICES][$ix] = $inside;
                }
                $inside = [];
            }
        }
        if( ! empty( $this->supplierInvoices )) {
            foreach( array_keys( $this->supplierInvoices ) as $ix ) {
                if( ! $this->supplierInvoices[$ix]->isValid( $inside )) {
                    $local[self::SUPPLIERINVOICES][$ix] = $inside;
                }
                $inside = [];
            }
        }
        if( ! empty( $this->fixedAssets )) {
            foreach( array_keys( $this->fixedAssets ) as $ix ) {
                if( ! $this->fixedAssets[$ix]->isValid( $inside )) {
                    $local[self::FIXEDASSETS][$ix] = $inside;
                }
                $inside = [];
            }
        }
        if( ! empty( $this->generalSubdividedAccount )) {
            foreach( array_keys( $this->generalSubdividedAccount ) as $ix ) {
                if( ! $this->generalSubdividedAccount[$ix]->isValid( $inside )) {
                    $local[self::GENERALSUBDIVIDEDACCOUNT][$ix] = $inside;
                }
                $inside = [];
            }
        }
        if( ! empty( $this->customers ) && ! $this->customers->isValid( $inside )) {
            $local[self::CUSTOMERS] = $inside;
            $inside = [];
        }
        if( ! empty( $this->suppliers ) && ! $this->suppliers->isValid( $inside )) {
            $local[self::SUPPLIERS] = $inside;
            $inside = [];
        }
        if( ! empty( $this->accountAggregations ) && ! $this->accountAggregations->isValid( $inside )) {
            $local[self::ACCOUNTAGGREGATIONS] = $inside;
            $inside = [];
        }
        if( ! empty( $this->journal )) {
            foreach( array_keys( $this->journal ) as $ix ) {
                if( ! $this->journal[$ix]->isValid( $inside )) {
                    $local[self::JOURNAL][$ix] = $inside;
                }
                $inside = [];
            }
        }
        if( ! empty( $this->documents ) && ! $this->documents->isValid( $inside )) {
            $local[self::DOCUMENTS] = $inside;
            $inside = [];
        }
        if( empty( $this->signature )) {
            $local[self::SIGNATURE] = self::SIGNATURE;
        }
        if( ! empty( $local )) {
            $expected[self::SIE] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return FileInfoType
     */
    public function getFileInfo() {
        return $this->fileInfo;
    }

    /**
     * @param FileInfoType $fileInfo
     * @return static
     */
    public function setFileInfo( FileInfoType $fileInfo ) {
        $this->fileInfo = $fileInfo;
        return $this;
    }

    /**
     * @return AccountsType
     */
    public function getAccounts() {
        return $this->accounts;
    }

    /**
     * Return array AccountsIds
     *
     * @return array
     */
    public function getAllAccountIds() {
        return $this->accounts->getAllAccountIds();
    }

    /**
     * Return int index if Account id is set or bool true if not
     *
     * @param int $id
     * @return int|bool  AccountType index or true if not found i.e. unique
     */
    public function isAccountIdUnique( $id ) {
        $hitIx = $this->accounts->isAccountIdUnique( $id );
        return ( true !== $hitIx ) ? $hitIx : true;
    }

    /**
     * @param AccountsType $accounts
     * @return static
     */
    public function setAccounts( AccountsType $accounts ) {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * @return DimensionsType
     */
    public function getDimensions() {
        return $this->dimensions;
    }

    /**
     * Return array DimensionIds
     *
     * @return array
     */
    public function getAllDimensionIds() {
        return $this->dimensions->getAllDimensionIds();
    }

    /**
     * Return int index if DimensionType id is set or bool true id not
     *
     * @param int $id
     * @return int|bool  DimensionType index or true if not found i.e unique
     */
    public function isDimensionsIdUnique( $id ) {
        $hitIx = $this->dimensions->isDimensionsIdUnique( $id );
        return ( false !== $hitIx ) ? $hitIx : true;
    }

    /**
     * @param DimensionsType $dimensionsType
     * @return static
     */
    public function setDimensions( DimensionsType $dimensionsType ) {
        $this->dimensions = $dimensionsType;
        return $this;
    }

    /**
     * @param CustomerInvoicesType $customerInvoices
     * @return static
     */
    public function addCustomerInvoices( CustomerInvoicesType $customerInvoices ) {
        $this->customerInvoices[] = $customerInvoices;
        return $this;
    }

    /**
     * @return array
     */
    public function getCustomerInvoices() {
        return $this->customerInvoices;
    }

    /**
     * Return array CustomerInvoices CustomerIds
     *
     * Two level array : customerInvoicesIx => [ *CustomerIds ]
     * @return array
     */
    public function getAllCustomerInvoicesCustomerIds() {
        $customerIds = [];
        foreach( array_keys( $this->customerInvoices ) as $ix ) {
            $customerIds[$ix] = $this->customerInvoices[$ix]->getAllCustomerInvoiceCustomerIds();
        }
        return $customerIds;
    }

    /**
     * @param array $customerInvoices  *CustomerInvoicesType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setCustomerInvoices( array $customerInvoices ) {
        foreach( $customerInvoices as $ix => $value ) {
            if( $value instanceof CustomerInvoicesType ) {
                $this->customerInvoices[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::CUSTOMERINVOICES, $ix, $type ));
            }
        }
        return $this;
    }

    /**
     * @param SupplierInvoicesType $supplierInvoices
     * @return static
     */
    public function addSupplierInvoices( SupplierInvoicesType $supplierInvoices ) {
        $this->supplierInvoices[] = $supplierInvoices;
        return $this;
    }

    /**
     * @return array
     */
    public function getSupplierInvoices() {
        return $this->supplierInvoices;
    }

    /**
     * Return array with all SupplierInvoices SupplierIds
     *
     * Two level array : supplierInvoicesIx => [ *SupplierIds ]
     * @return array
     */
    public function getAllSupplierInvoicesSupplierIds() {
        $supplierIds = [];
        foreach( array_keys( $this->supplierInvoices ) as $ix ) {
            $supplierIds[$ix] = $this->supplierInvoices[$ix]->getAllSupplierInvoiceSupplierIds();
        }
        return $supplierIds;
    }

    /**
     * @param array $supplierInvoices  *SupplierInvoicesType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setSupplierInvoices( array $supplierInvoices ) {
        foreach( $supplierInvoices as $ix => $value ) {
            if( $value instanceof SupplierInvoicesType ) {
                $this->supplierInvoices[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::SUPPLIERINVOICES, $ix, $type ));
            }
        }
        return $this;
    }

    /**
     * @param FixedAssetsType $fixedAsset
     * @return static
     */
    public function addFixedAsset( FixedAssetsType $fixedAsset ) {
        $this->fixedAssets[] = $fixedAsset;
        return $this;
    }

    /**
     * @return array
     */
    public function getFixedAssets() {
        return $this->fixedAssets;
    }

    /**
     * @param array $fixedAssets  *FixedAssetsType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setFixedAssets( array $fixedAssets ) {
        foreach( $fixedAssets as $ix => $value ) {
            if( $value instanceof FixedAssetsType ) {
                $this->fixedAssets[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::FIXEDASSETS, $ix, $type ));
            }
        }
        return $this;
    }

    /**
     * @param GeneralSubdividedAccountType $generalSubdividedAccount
     * @return static
     */
    public function addGeneralSubdividedAccount( GeneralSubdividedAccountType $generalSubdividedAccount ) {
        $this->generalSubdividedAccount[] = $generalSubdividedAccount;
        return $this;
    }

    /**
     * @return array
     */
    public function getGeneralSubdividedAccount() {
        return $this->generalSubdividedAccount;
    }

    /**
     * @param array $generalSubdividedAccount   *GeneralSubdividedAccountType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setGeneralSubdividedAccount( array $generalSubdividedAccount ) {
        foreach( $generalSubdividedAccount as $ix => $value ) {
            if( $value instanceof GeneralSubdividedAccountType ) {
                $this->generalSubdividedAccount[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::GENERALSUBDIVIDEDACCOUNT, $ix, $type ));
            }
        }
        return $this;
    }

    /**
     * @return CustomersType
     */
    public function getCustomers() {
        return $this->customers;
    }

    /**
     * @param CustomersType $customers
     * @return static
     */
    public function setCustomers( CustomersType $customers ) {
        $this->customers = $customers;
        return $this;
    }

    /**
     * @return SuppliersType
     */
    public function getSuppliers() {
        return $this->suppliers;
    }

    /**
     * @param SuppliersType $suppliers
     * @return static
     */
    public function setSuppliers( SuppliersType $suppliers ) {
        $this->suppliers = $suppliers;
        return $this;
    }

    /**
     * @return AccountAggregationsType
     */
    public function getAccountAggregations() {
        return $this->accountAggregations;
    }

    /**
     * @param AccountAggregationsType $accountAggregations
     * @return static
     */
    public function setAccountAggregations( AccountAggregationsType $accountAggregations ) {
        $this->accountAggregations = $accountAggregations;
        return $this;
    }

    /**
     * @param JournalType $journal
     * @return static
     */
    public function addJournal( JournalType $journal ) {
        $this->journal[] = $journal;
        $this->sortJournal();
        return $this;
    }

    /**
     * @return array
     */
    public function getJournal() {
        return $this->journal;
    }

    /**
     * Return array with all journalEntry ledgerEntry AccountsIds
     *
     * @return array
     */
    public function getAllJournalEntryLedgerEntryAccountIds() {
        $accountIds = [];
        foreach( array_keys( $this->journal ) as $ix ) {
            $accountIds = array_merge( $accountIds, $this->journal[$ix]->getAllJournalEntryLedgerEntryAccountIds());
        }
        sort( $accountIds );
        return array_unique( $accountIds );
    }

    /**
     * Return array with all journalEntry VoucherReference dokumentIds
     *
     * Three level array: journalIx, journalEntryIx, VoucherReferenceIx
     * @return array
     */
    public function getAllJournalEntryVoucherReferenceDocumentIds() {
        $documentIds = [];
        foreach( array_keys( $this->journal ) as $ix ) {
            $documentIds[$ix] = $this->journal[$ix]->getAllJournalEntryVoucherReferenceDocumentIds();
        }
        return $documentIds;
    }

    /**
     * Return bool true if sum of each journalEntry ledgerEntries amount is zero
     *
     * @param array $errorIx
     * @return bool|string (bool true on success, on error indexes (ix-ix) of ledgerEntry/LedgerEntry)
     */
    public function hasBalancedJournalLedgerEntries( & $errorIx = [] ) {
        foreach( array_keys( $this->journal ) as $ix1 ) {
            $errorIx2 = [];
            $ix2 = $this->journal[$ix1]->hasBalancedJournalEntryLedgerEntries( $errorIx2 );
            if( true !== $ix2 ) {
                $errorIx[$ix1] = $errorIx2;
            }
        }
        return ( empty( $errorIx ));
    }

    /**
     * @param array $journal   *JournalType
     * @return static
     * @throws InvalidArgumentException
     */
    public function setJournal( array $journal ) {
        foreach( $journal as $ix => $value ) {
            if( $value instanceof JournalType ) {
                $this->journal[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::JOURNAL, $ix, $type ));
            }
        }
        $this->sortJournal();
        return $this;
    }

    /**
     * Sort Journal on id, JournalEntries on id and journalDate
     *
     * @return static
     */
    public function sortJournal() {
        foreach( array_keys( $this->journal ) as $ix ) {
            $this->journal[$ix]->sortJournalEntryOnId();
        }
        usort( $this->journal, SortFactory::$journalTypeSorter );
        return $this;
    }

    /**
     * @return DocumentsType
     */
    public function getDocuments() {
        return $this->documents;
    }

    /**
     * Return array with all dokumentType ids
     *
     * @return array
     */
    public function getAllDocumentsTypeIds() {
        return $this->documents->getAllDocumentsTypeIds();
    }

    /**
     * Return bool true if Document id is set
     *
     * @param int $id
     * @return bool  true on found, false if not
     */
    public function isDocumentIdUnique( $id ) {
        return $this->documents->isDocumentIdUnique( $id );
    }

    /**
     * @param DocumentsType $documents
     * @return static
     */
    public function setDocuments( DocumentsType $documents ) {
        $this->documents = $documents;
        return $this;
    }

    /**
     * @return SignatureType
     */
    public function getSignature() {
        return $this->signature;
    }

    /**
     * @param SignatureType $signature
     * @return static
     */
    public function setSignature( SignatureType $signature ) {
        $this->signature = $signature;
        return $this;
    }

}
