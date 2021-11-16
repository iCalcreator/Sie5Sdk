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
namespace Kigkonsult\Sie5Sdk;

interface Sie5Interface
{
    /**
     * Product constants
     */
    public const PRODUCTNAME              = 'Kigkonsult\Sie5Sdk';
    public const PRODUCTVERSION           = '1.0';

    /**
     * Sie element constants
     */
    public const ACCOUNT                  = 'Account';
    public const ACCOUNTAGGREGATION       = 'AccountAggregation';
    public const ACCOUNTAGGREGATIONS      = 'AccountAggregations';
    public const ACCOUNTINGCURRENCY       = 'AccountingCurrency';
    public const ACCOUNTREF               = 'AccountRef';
    public const ACCOUNTS                 = 'Accounts';
    public const BALANCES                 = 'Balances';
    public const BASEBALANCE              = 'BaseBalance';
    public const BASEBALANCEMULTIDIM      = 'BaseBalanceMultidim';
    public const BUDGET                   = 'Budget';
    public const BUDGETMULTIDIM           = 'BudgetMultidim';
    public const CLOSINGBALANCE           = 'ClosingBalance';
    public const CLOSINGBALANCEMULTIDIM   = 'ClosingBalanceMultidim';
    public const COMPANY                  = 'Company';
    public const CORRECTEDBY              = 'CorrectedBy';
    public const CUSTOMER                 = 'Customer';
    public const CUSTOMERINVOICE          = 'CustomerInvoice';
    public const CUSTOMERINVOICES         = 'CustomerInvoices';
    public const CUSTOMERS                = 'Customers';
    public const DIMENSION                = 'Dimension';
    public const DIMENSIONS               = 'Dimensions';
    public const DOCUMENTS                = 'Documents';
    public const EMBEDDEDFILE             = 'EmbeddedFile';
    public const ENTRYINFO                = 'EntryInfo';
    public const FILECREATION             = 'FileCreation';
    public const FILEINFO                 = 'FileInfo';
    public const FILEREFERENCE            = 'FileReference';
    public const FISCALYEAR               = 'FiscalYear';
    public const FISCALYEARS              = 'FiscalYears';
    public const FIXEDASSET               = 'FixedAsset';
    public const FIXEDASSETS              = 'FixedAssets';
    public const FOREIGNCURRENCYAMOUNT    = 'ForeignCurrencyAmount';
    public const GENERALOBEJCT            = 'GeneralObject';
    public const GENERALSUBDIVIDEDACCOUNT = 'GeneralSubdividedAccount';
    public const JOURNAL                  = 'Journal';
    public const JOURNALENTRY             = 'JournalEntry';
    public const LEDGERENTRY              = 'LedgerEntry';
    public const LOCKINGINFO              = 'LockingInfo';
    public const OBJECT                   = 'Object';
    public const OBJECTREFERENCE          = 'ObjectReference';
    public const OPENINGBALANCE           = 'OpeningBalance';
    public const OPENINGBALANCEMULTIDIM   = 'OpeningBalanceMultidim';
    public const ORIGINALAMOUNT           = 'OriginalAmount';
    public const ORIGINALENTRYINFO        = 'OriginalEntryInfo';
    public const OVERSTRIKE               = 'Overstrike';
    public const SECONDARYACCOUNTREF      = 'SecondaryAccountRef';
    public const SIE                      = 'Sie';
    public const SIEENTRY                 = 'SieEntry';
    public const SIGNATURE                = 'Signature';
    public const SOFTWAREPRODUCT          = 'SoftwareProduct';
    public const SUBDIVIDEDACCOUNTOBJECTREFERENCE
                                   = 'SubdividedAccountObjectReference';
    public const SUBDIVIDEDACCOUNTOBJECT  = 'SubdividedAccountObjectType';
    public const SUPPLIER                 = 'Supplier';
    public const SUPPLIERINVOICE          = 'SupplierInvoice';
    public const SUPPLIERINVOICES         = 'SupplierInvoices';
    public const SUPPLIERS                = 'Suppliers';
    public const TAG                      = 'Tag';
    public const VOUCHERREFERENCE         = 'VoucherReference';

    /**
     * Sie attribute constants
     */
    public const ACCOUNTID                = 'accountId';
    public const ADDRESS1                 = 'address1';
    public const ADDRESS2                 = 'address2';
    public const AMOUNT                   = 'amount';
    public const BIC                      = 'BIC';
    public const BY                       = 'by';
    public const BGACCOUNT                = 'BgAccount';
    public const CITY                     = 'city';
    public const CLIENTID                 = 'clientId';
    public const CLOSED                   = 'closed';
    public const COUNTRY                  = 'country';
    public const CURRENCY                 = 'currency';
    public const CUSTOMERID               = 'customerId';
    public const DATE                     = 'date';
    public const DIMID                    = 'dimId';
    public const DOCUMENTID               = 'documentId';
    public const DUEDATE                  = 'dueDate';
    public const END                      = 'end';
    public const FALSE                    = 'false';
    public const FILENAME                 = 'fileName';
    public const FISCALYEARID             = 'fiscalYearId';
    public const HASATTACHEDVOUCHERFILES  = 'hasAttachedVoucherFiles';
    public const HASLEDGERENTRIES         = 'hasLedgerEntries';
    public const HASSUBORDINATEACCOUNTS   = 'hasSubordinateAccounts';
    public const IBAN                     = 'IBAN';
    public const ID                       = 'id';
    public const INVOICENUMBER            = 'invoiceNumber';
    public const JOURNALDATE              = 'journalDate';
    public const JOURNALENTRYID           = 'journalEntryId';
    public const JOURNALID                = 'journalId';
    public const LASTCOVEREDDATE          = 'lastCoveredDate';
    public const LEDGERDATE               = 'ledgerDate';
    public const MONTH                    = 'month';
    public const MULTIPLE                 = 'multiple';
    public const NAME                     = 'name';
    public const OBJECTID                 = 'objectId';
    public const OCRNUMBER                = 'ocrNumber';
    public const ORGANIZATIONID           = 'organizationId';
    public const PGACCOUNT                = 'PgAccount';
    public const PRIMARY                  = 'primary';
    public const PRIMARYACCOUNTID         = 'primaryAccountId';
    public const QUANTITY                 = 'quantity';
    public const REFERENCEID              = 'referenceId';
    public const START                    = 'start';
    public const SUPPLIERID               = 'supplierId';
    public const TAXONOMY                 = 'taxonomy';
    public const TEXT                     = 'text';
    public const TIME                     = 'time';
    public const TYPE                     = 'type';
    public const TRUE                     = 'true';
    public const UNIT                     = 'unit';
    public const URI                      = 'URI';
    public const VATNR                    = 'vatNr';
    public const VERSION                  = 'version';
    public const ZIPCODE                  = 'zipcode';

    /**
     * Sie type attribute value constants
     */
    public const ASSET                    = 'asset';
    public const COST                     = 'cost';
    public const EQUITY                   = 'equity';
    public const INCOME                   = 'income';
    public const LIABILITY                = 'liability';
    public const STATISTICS               = 'statistics';

    /**
     * const Sie5 URis
     */
    public const SIE5SCHEMALOCATION       = "http://www.sie.se/sie5 http://www.sie.se/sie5.xsd";
    public const SIE5URI                  = "http://www.sie.se/sie5";
}
