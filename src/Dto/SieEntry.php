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

use Kigkonsult\DsigSdk\Dto\SignatureType;
use Kigkonsult\Sie5Sdk\Impl\SortFactory;
use TypeError;

use function array_keys;
use function array_unique;
use function sort;
use function usort;

/**
 * Class SieEntry3
 *
 * Root element for entry file
 */
class SieEntry extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var FileInfoTypeEntry
     *
     * Attribute maxOccurs="1" minOccurs="1"
     * General information about the file
     */
    private $fileInfo = null;

    /**
     * @var AccountsTypeEntry
     *
     * Attribute minOccurs="0"
     * Chart of accounts
     */
    private $accounts = null;

    /**
     * @var DimensionsTypeEntry
     *
     * Attribute minOccurs="0"
     * Container for dimensions
     */
    private $dimensions = null;

    /**
     * @var CustomerInvoicesTypeEntry[]
     *
     * Attribute minOccurs="0" maxOccurs="unbounded"
     */
    private $customerInvoices = [];

    /**
     * @var SupplierInvoicesTypeEntry[]
     *
     * Attribute minOccurs="0" maxOccurs="unbounded"
     */
    private $supplierInvoices = [];

    /**
     * @var FixedAssetsTypeEntry[]
     *
     *   Attribute minOccurs="0" maxOccurs="unbounded"
     */
    private $fixedAssets = [];

    /**
     * @var GeneralSubdividedAccountTypeEntry[]
     *
     * Attribute minOccurs="0" maxOccurs="unbounded"
     */
    private $generalSubdividedAccount = [];

    /**
     * @var CustomersType
     *
     * Attribute minOccurs="0"
     * Container for customers
     */
    private $customers = null;

    /**
     * @var SuppliersType
     *
     * Attribute minOccurs="0"
     * Container for suppliers
     */
    private $suppliers = null;

    /**
     * @var JournalTypeEntry[]
     *
     * Attribute minOccurs="0"  maxOccurs="unbounded"
     * Container for individual journal
     */
    private $journal = [];

    /**
     * @var DocumentsType
     *
     * Attribute minOccurs="0"
     * Container for documents
     */
    private $documents = null;

    /**
     * @var SignatureType
     *
     * Attribute minOccurs="0"
     */
    private $signature = null;

    /**
     * Return bool true is instance is valid
     *
     * @param array $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null ) : bool
    {
        $local = $inside = [];
        if( empty( $this->fileInfo )) {
            $local[] = self::errMissing(self::class, self::FILEINFO );
        }
        elseif( ! $this->fileInfo->isValid( $inside )) {
            $local[] = $inside;
            $inside = [];
        }
        if( ! empty( $this->accounts ) &&
            ! $this->accounts->isValid( $inside )) {
            $local[] = $inside;
            $inside = [];
        }
        if( ! empty( $this->dimensions ) &&
            ! $this->dimensions->isValid( $inside )) {
            $local[] = $inside;
            $inside = [];
        }
        if( ! empty( $this->customerInvoices )) {
            foreach( array_keys( $this->customerInvoices ) as $ix ) {
                $inside[$ix] = [];
                if( $this->customerInvoices[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::CUSTOMERINVOICES );
                $local[$key] = $inside;
                $inside      = [];
            } // end if
        } // end if
        if( ! empty( $this->supplierInvoices )) {
            foreach( array_keys( $this->supplierInvoices ) as $ix ) {
                $inside[$ix] = [];
                if( $this->supplierInvoices[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::SUPPLIERINVOICES );
                $local[$key] = $inside;
                $inside      = [];
            } // end if
        } // end if
        if( ! empty( $this->fixedAssets )) {
            foreach( array_keys( $this->fixedAssets ) as $ix ) {
                $inside[$ix] = [];
                if( $this->fixedAssets[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::FIXEDASSETS );
                $local[$key] = $inside;
                $inside      = [];
            } // end if
        } // end if
        if( ! empty( $this->generalSubdividedAccount )) {
            foreach( array_keys( $this->generalSubdividedAccount ) as $ix ) {
                $inside[$ix] = [];
                if( $this->generalSubdividedAccount[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::GENERALSUBDIVIDEDACCOUNT );
                $local[$key] = $inside;
                $inside      = [];
            } // end if
        } // end if
        if( ! empty( $this->customers ) &&
            ! $this->customers->isValid( $inside )) {
            $local[] = $inside;
            $inside = [];
        }
        if( ! empty( $this->suppliers ) &&
            ! $this->suppliers->isValid( $inside )) {
            $local[] = $inside;
            $inside = [];
        }
        if( ! empty( $this->journal )) {
            foreach( array_keys( $this->journal ) as $ix ) {
                $inside[$ix] = [];
                if( $this->journal[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::JOURNAL );
                $local[$key] = $inside;
                $inside      = [];
            } // end if
        } // end if
        if( ! empty( $this->documents ) &&
            ! $this->documents->isValid( $inside )) {
            $local[] = $inside;
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return null|FileInfoTypeEntry
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * @param FileInfoTypeEntry $fileInfo
     * @return static
     */
    public function setFileInfo( FileInfoTypeEntry $fileInfo ) : self
    {
        $this->fileInfo = $fileInfo;
        return $this;
    }

    /**
     * @return null|AccountsTypeEntry
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Return array AccountsIds
     *
     * @return array
     */
    public function getAllAccountIds() : array
    {
        return $this->accounts->getAllAccountIds();
    }

    /**
     * Return int index if Account id is set or bool true if not
     *
     * @param string $id
     * @return int|bool  AccountType index or true if not found i.e. unique
     */
    public function isAccountIdUnique( string $id )
    {
        $hitIx = $this->accounts->isAccountIdUnique( $id );
        return ( false !== $hitIx ) ? $hitIx : true;
    }

    /**
     * @param AccountsTypeEntry $accounts
     * @return static
     */
    public function setAccounts( AccountsTypeEntry $accounts ) : self
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * @return null|DimensionsTypeEntry
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Return array DimensionIds
     *
     * @return array
     */
    public function getAllDimensionIds() : array
    {
        return $this->dimensions->getAllDimensionIds();
    }

    /**
     * Return int index if DimensionType id is set or bool true if not
     *
     * @param int $id
     * @return int|bool  DimensionType index or true if not found i.e. unique
     */
    public function isDimensionsIdUnique( int $id )
    {
        $hitIx = $this->dimensions->isDimensionsIdUnique( $id );
        return ( true !== $hitIx ) ? $hitIx : true;
    }

    /**
     * @param DimensionsTypeEntry $dimensions
     * @return static
     */
    public function setDimensions( DimensionsTypeEntry $dimensions ) : self
    {
        $this->dimensions = $dimensions;
        return $this;
    }

    /**
     * Add single CustomerInvoicesTypeEntry
     *
     * @param CustomerInvoicesTypeEntry $customerInvoices
     * @return static
     */
    public function addCustomerInvoices( CustomerInvoicesTypeEntry $customerInvoices ) : self
    {
        $this->customerInvoices[] = $customerInvoices;
        return $this;
    }

    /**
     * @return array
     */
    public function getCustomerInvoices() : array
    {
        return $this->customerInvoices;
    }

    /**
     * Return array CustomerInvoices CustomerIds
     *
     * Two level array : customerInvoicesIx => [ *CustomerIds ]
     * @return array
     */
    public function getAllCustomerInvoicesCustomerIds() : array
    {
        $customerIds = [];
        foreach( array_keys( $this->customerInvoices ) as $ix ) {
            $customerIds[$ix] = $this->customerInvoices[$ix]->getAllCustomerInvoiceCustomerIds();
        } // end foreach
        return $customerIds;
    }

    /**
     * Set CustomerInvoicesTypeEntry's, array
     *
     * @param CustomerInvoicesTypeEntry[] $customerInvoices
     * @return static
     * @throws TypeError
     */
    public function setCustomerInvoices( array $customerInvoices ) : self
    {
        foreach( $customerInvoices as $value ) {
            $this->addCustomerInvoices( $value );
        } // end foreach
        return $this;
    }

    /**
     * Add single SupplierInvoicesTypeEntry
     *
     * @param SupplierInvoicesTypeEntry $supplierInvoices
     * @return static
     */
    public function addSupplierInvoices( SupplierInvoicesTypeEntry $supplierInvoices ) : self
    {
        $this->supplierInvoices[] = $supplierInvoices;
        return $this;
    }

    /**
     * @return array
     */
    public function getSupplierInvoices() : array
    {
        return $this->supplierInvoices;
    }

    /**
     * Return array with all SupplierInvoices SupplierIds
     *
     * Two level array : supplierInvoicesIx => [ *SupplierIds ]
     * @return array
     */
    public function getAllSupplierInvoicesSupplierIds() : array
    {
        $supplierIds = [];
        foreach( array_keys( $this->supplierInvoices ) as $ix ) {
            $supplierIds[$ix] = $this->supplierInvoices[$ix]->getAllSupplierInvoiceSupplierIds();
        } // end foreach
        return $supplierIds;
    }

    /**
     * Set SupplierInvoicesTypeEntry's array
     *
     * @param SupplierInvoicesTypeEntry[] $supplierInvoices
     * @return static
     * @throws TypeError
     */
    public function setSupplierInvoices( array $supplierInvoices ) : self
    {
        foreach( $supplierInvoices as $value ) {
            $this->addSupplierInvoices( $value );
        } // end foreach
        return $this;
    }

    /**
     * Add single FixedAssetsTypeEntry
     *
     * @param FixedAssetsTypeEntry $fixedAsset
     * @return static
     */
    public function addFixedAsset( FixedAssetsTypeEntry $fixedAsset ) : self
    {
        $this->fixedAssets[] = $fixedAsset;
        return $this;
    }

    /**
     * @return FixedAssetsTypeEntry[]
     */
    public function getFixedAssets() : array
    {
        return $this->fixedAssets;
    }

    /**
     * Set FixedAssetsTypeEntry's, array
     *
     * @param FixedAssetsTypeEntry[] $fixedAssets
     * @return static
     * @throws TypeError
     */
    public function setFixedAssets( array $fixedAssets ) : self
    {
        foreach( $fixedAssets as $value ) {
            $this->addFixedAsset( $value );
        } // end foreach
        return $this;
    }

    /**
     * Add single GeneralSubdividedAccountTypeEntry
     *
     * @param GeneralSubdividedAccountTypeEntry $generalSubdividedAccount
     * @return static
     */
    public function addGeneralSubdividedAccount(
        GeneralSubdividedAccountTypeEntry $generalSubdividedAccount
    ) : self
    {
        $this->generalSubdividedAccount[] = $generalSubdividedAccount;
        return $this;
    }

    /**
     * @return GeneralSubdividedAccountTypeEntry[]
     */
    public function getGeneralSubdividedAccount() : array
    {
        return $this->generalSubdividedAccount;
    }

    /**
     * Set GeneralSubdividedAccountTypeEntry''s, array
     *
     * @param GeneralSubdividedAccountTypeEntry[] $generalSubdividedAccount
     * @return static
     * @throws TypeError
     */
    public function setGeneralSubdividedAccount( array $generalSubdividedAccount ) : self
    {
        foreach( $generalSubdividedAccount as $value ) {
            $this->addGeneralSubdividedAccount( $value );
        } // end foreach
        return $this;
    }

    /**
     * @return CustomersType
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @param CustomersType $customers
     * @return static
     */
    public function setCustomers( CustomersType $customers ) : self
    {
        $this->customers = $customers;
        return $this;
    }

    /**
     * @return null|SuppliersType
     */
    public function getSuppliers()
    {
        return $this->suppliers;
    }

    /**
     * @param SuppliersType $suppliers
     * @return static
     */
    public function setSuppliers( SuppliersType $suppliers ) : self
    {
        $this->suppliers = $suppliers;
        return $this;
    }

    /**
     * Add single JournalTypeEntry
     *
     * @param JournalTypeEntry $journal
     * @return static
     */
    public function addJournal( JournalTypeEntry $journal ) : self
    {
        $this->journal[] = $journal;
        return $this;
    }

    /**
     * @return JournalTypeEntry[]
     */
    public function getJournal() : array
    {
        $this->sortJournal();
        return $this->journal;
    }

    /**
     * Return array with all journalEntry ledgerEntry AccountsIds
     *
     * @return array
     */
    public function getAllJournalEntryLedgerEntryAccountIds() : array
    {
        $accountIds = [];
        foreach( array_keys( $this->journal ) as $ix1 ) {
            $accountIds = array_merge( $accountIds, $this->journal[$ix1]->getAllJournalEntryLedgerEntryAccountIds());
        } // end foreach
        sort( $accountIds );
        return array_unique( $accountIds );
    }

    /**
     * Return array with all journalEntry VoucherReference dokumentIds
     *
     * @return array
     */
    public function getAllJournalEntryVoucherReferenceDocumentIds() : array
    {
        $documentIds = [];
        foreach( array_keys( $this->journal ) as $ix ) {
            $documentIds[$ix] = $this->journal[$ix]->getAllJournalEntryVoucherReferenceDocumentIds();
        } // end foreach
        return $documentIds;
    }

    /**
     * Return bool true if sum of each journalEntry ledgerEntries amount is zero
     *
     * @param array $errorIx
     * @return bool  bool true on success, on error $errorIx holds error Journal LedgerEntry indexes
     */
    public function hasBalancedJournalLedgerEntries( & $errorIx = [] ) : bool
    {
        foreach( array_keys( $this->journal ) as $ix1 ) {
            $errorIx2 = [];
            $ix2 = $this->journal[$ix1]->hasBalancedJournalEntryLedgerEntries( $errorIx2 );
            if( true !== $ix2 ) {
                $errorIx[$ix1] = $errorIx2;
            }
        } // end foreach
        return ( empty( $errorIx ));
    }

    /**
     * Set JournalTypeEntry's, array
     *
     * @param JournalTypeEntry[] $journal
     * @return static
     * @throws TypeError
     */
    public function setJournal( array $journal ) : self
    {
        foreach( $journal as $value ) {
            $this->addJournal( $value );
        } // end foreach
        return $this;
    }

    /**
     * Sort Journal on id, JournalEntries on id and journalDate
     *
     * @return static
     */
    public function sortJournal() : self
    {
        foreach( array_keys( $this->journal ) as $ix ) {
            $this->journal[$ix]->sortJournalEntryOnId();
        } // end foreach
        usort( $this->journal, SortFactory::$journalTypeEntrySorter );
        return $this;
    }

    /**
     * @return null|DocumentsType
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Return array with all dokumentType ids
     *
     * @return array
     */
    public function getAllDocumentsTypeIds() : array
    {
        return $this->documents->getAllDocumentsTypeIds();
    }

    /**
     * Return bool true if Document id is set
     *
     * @param int $id
     * @return bool  false on found, true if not i.e. unique
     */
    public function isDocumentIdUnique( int $id ) : bool
    {
        return $this->documents->isDocumentIdUnique( $id );
    }

    /**
     * @param DocumentsType $documents
     * @return static
     */
    public function setDocuments( DocumentsType $documents ) : self
    {
        $this->documents = $documents;
        return $this;
    }

    /**
     * @return null|SignatureType
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param SignatureType $signature
     * @return static
     */
    public function setSignature( SignatureType $signature ) : self
    {
        $this->signature = $signature;
        return $this;
    }
}
