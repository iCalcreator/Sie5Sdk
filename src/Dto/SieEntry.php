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

use InvalidArgumentException;
use Kigkonsult\DsigSdk\Dto\SignatureType;
use Kigkonsult\Sie5Sdk\Impl\SortFactory;

use function array_keys;
use function array_unique;
use function gettype;
use function sort;
use function sprintf;
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
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) : bool
    {
        $local = $inside = [];
        if( empty( $this->fileInfo )) {
            $local[self::FILEINFO] = false;
        }
        elseif( ! $this->fileInfo->isValid( $inside )) {
            $local[self::FILEINFO] = $inside;
            $inside = [];
        }
        if( ! empty( $this->accounts ) && ! $this->accounts->isValid( $inside )) {
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
            } // end foreach
        }
        if( ! empty( $this->supplierInvoices )) {
            foreach( array_keys( $this->supplierInvoices ) as $ix ) {
                if( ! $this->supplierInvoices[$ix]->isValid( $inside )) {
                    $local[self::SUPPLIERINVOICES][$ix] = $inside;
                }
                $inside = [];
            } // end foreach
        }
        if( ! empty( $this->fixedAssets )) {
            foreach( array_keys( $this->fixedAssets ) as $ix ) {
                if( ! $this->fixedAssets[$ix]->isValid( $inside )) {
                    $local[self::FIXEDASSETS][$ix] = $inside;
                }
                $inside = [];
            } // end foreach
        }
        if( ! empty( $this->generalSubdividedAccount )) {
            foreach( array_keys( $this->generalSubdividedAccount ) as $ix ) {
                if( ! $this->generalSubdividedAccount[$ix]->isValid( $inside )) {
                    $local[self::GENERALSUBDIVIDEDACCOUNT][$ix] = $inside;
                }
                $inside = [];
            } // end foreach
        }
        if( ! empty( $this->customers ) && ! $this->customers->isValid( $inside )) {
            $local[self::CUSTOMERS] = $inside;
            $inside = [];
        }
        if( ! empty( $this->suppliers ) && ! $this->suppliers->isValid( $inside )) {
            $local[self::SUPPLIERS] = $inside;
            $inside = [];
        }
        if( ! empty( $this->journal )) {
            foreach( array_keys( $this->journal ) as $ix ) {
                if( ! $this->journal[$ix]->isValid( $inside )) {
                    $local[self::JOURNAL][$ix] = $inside;
                }
                $inside = [];
            } // end foreach
        }
        if( ! empty( $this->documents ) && ! $this->documents->isValid( $inside )) {
            $local[self::DOCUMENTS] = $inside;
        }
        if( ! empty( $local )) {
            $expected[self::SIEENTRY] = $local;
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
     * @param array $customerInvoices   *CustomerInvoicesTypeEntry
     * @return static
     * @throws InvalidArgumentException
     */
    public function setCustomerInvoices( array $customerInvoices ) : self
    {
        foreach( $customerInvoices as $ix => $value ) {
            if( $value instanceof CustomerInvoicesTypeEntry ) {
                $this->customerInvoices[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::CUSTOMERINVOICES, $ix, $type ));
            }
        } // end foreach
        return $this;
    }

    /**
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
     * @param array $supplierInvoices   *SupplierInvoicesTypeEntry
     * @return static
     * @throws InvalidArgumentException
     */
    public function setSupplierInvoices( array $supplierInvoices ) : self
    {
        foreach( $supplierInvoices as $ix => $value ) {
            if( $value instanceof SupplierInvoicesTypeEntry ) {
                $this->supplierInvoices[] = $value;
                continue;
            }
            $type = gettype( $value );
            if( self::$OBJECT == $type ) {
                $type = get_class( $value );
            }
            throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::SUPPLIERINVOICES, $ix, $type ));
        } // end foreach
        return $this;
    }

    /**
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
     * @param FixedAssetsTypeEntry[] $fixedAssets
     * @return static
     * @throws InvalidArgumentException
     */
    public function setFixedAssets( array $fixedAssets ) : self
    {
        foreach( $fixedAssets as $ix => $value ) {
            if( $value instanceof FixedAssetsTypeEntry ) {
                $this->fixedAssets[] = $value;
                continue;
            }
            $type = gettype( $value );
            if( self::$OBJECT == $type ) {
                $type = get_class( $value );
            }
            throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::FIXEDASSETS, $ix, $type ));
        } // end foreach
        return $this;
    }

    /**
     * @param GeneralSubdividedAccountTypeEntry $generalSubdividedAccount
     * @return static
     */
    public function addGeneralSubdividedAccount( GeneralSubdividedAccountTypeEntry $generalSubdividedAccount ) : self
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
     * @param GeneralSubdividedAccountTypeEntry[] $generalSubdividedAccount
     * @return static
     * @throws InvalidArgumentException
     */
    public function setGeneralSubdividedAccount( array $generalSubdividedAccount ) : self
    {
        foreach( $generalSubdividedAccount as $ix => $value ) {
            if( $value instanceof GeneralSubdividedAccountTypeEntry ) {
                $this->generalSubdividedAccount[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException(
                    sprintf( self::$FMTERR1, self::GENERALSUBDIVIDEDACCOUNT, $ix, $type )
                );
            }
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
     * @param JournalTypeEntry $journal
     * @return static
     */
    public function addJournal( JournalTypeEntry $journal ) : self
    {
        $this->journal[] = $journal;
        $this->sortJournal();
        return $this;
    }

    /**
     * @return JournalTypeEntry[]
     */
    public function getJournal() : array
    {
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
     * @param JournalTypeEntry[] $journal
     * @return static
     * @throws InvalidArgumentException
     */
    public function setJournal( array $journal ) : self
    {
        foreach( $journal as $ix => $value ) {
            if( $value instanceof JournalTypeEntry ) {
                $this->journal[$ix] = $value;
            }
            else {
                $type = gettype( $value );
                if( self::$OBJECT == $type ) {
                    $type = get_class( $value );
                }
                throw new InvalidArgumentException( sprintf( self::$FMTERR1, self::JOURNAL, $ix, $type ));
            }
        } // end foreach
        $this->sortJournal();
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
