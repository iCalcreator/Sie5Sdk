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
    const PRODUCTNAME              = 'Kigkonsult\Sie5Sdk';
    const PRODUCTVERSION           = '1.0';

    /**
     * Sie element constants
     */
    const ACCOUNT                  = 'Account';
    const ACCOUNTAGGREGATION       = 'AccountAggregation';
    const ACCOUNTAGGREGATIONS      = 'AccountAggregations';
    const ACCOUNTINGCURRENCY       = 'AccountingCurrency';
    const ACCOUNTREF               = 'AccountRef';
    const ACCOUNTS                 = 'Accounts';
    const BALANCES                 = 'Balances';
    const BASEBALANCE              = 'BaseBalance';
    const BASEBALANCEMULTIDIM      = 'BaseBalanceMultidim';
    const BUDGET                   = 'Budget';
    const BUDGETMULTIDIM           = 'BudgetMultidim';
    const CLOSINGBALANCE           = 'ClosingBalance';
    const CLOSINGBALANCEMULTIDIM   = 'ClosingBalanceMultidim';
    const COMPANY                  = 'Company';
    const CORRECTEDBY              = 'CorrectedBy';
    const CUSTOMER                 = 'Customer';
    const CUSTOMERINVOICE          = 'CustomerInvoice';
    const CUSTOMERINVOICES         = 'CustomerInvoices';
    const CUSTOMERS                = 'Customers';
    const DIMENSION                = 'Dimension';
    const DIMENSIONS               = 'Dimensions';
    const DOCUMENTS                = 'Documents';
    const EMBEDDEDFILE             = 'EmbeddedFile';
    const ENTRYINFO                = 'EntryInfo';
    const FILECREATION             = 'FileCreation';
    const FILEINFO                 = 'FileInfo';
    const FILEREFERENCE            = 'FileReference';
    const FISCALYEAR               = 'FiscalYear';
    const FISCALYEARS              = 'FiscalYears';
    const FIXEDASSET               = 'FixedAsset';
    const FIXEDASSETS              = 'FixedAssets';
    const FOREIGNCURRENCYAMOUNT    = 'ForeignCurrencyAmount';
    const GENERALOBEJCT            = 'GeneralObject';
    const GENERALSUBDIVIDEDACCOUNT = 'GeneralSubdividedAccount';
    const JOURNAL                  = 'Journal';
    const JOURNALENTRY             = 'JournalEntry';
    const LEDGERENTRY              = 'LedgerEntry';
    const LOCKINGINFO              = 'LockingInfo';
    const OBJECT                   = 'Object';
    const OBJECTREFERENCE          = 'ObjectReference';
    const OPENINGBALANCE           = 'OpeningBalance';
    const OPENINGBALANCEMULTIDIM   = 'OpeningBalanceMultidim';
    const ORIGINALAMOUNT           = 'OriginalAmount';
    const ORIGINALENTRYINFO        = 'OriginalEntryInfo';
    const OVERSTRIKE               = 'Overstrike';
    const SECONDARYACCOUNTREF      = 'SecondaryAccountRef';
    const SIE                      = 'Sie';
    const SIEENTRY                 = 'SieEntry';
    const SIGNATURE                = 'Signature';
    const SOFTWAREPRODUCT          = 'SoftwareProduct';
    const SUBDIVIDEDACCOUNTOBJECTREFERENCE
                                   = 'SubdividedAccountObjectReference';
    const SUBDIVIDEDACCOUNTOBJECT  = 'SubdividedAccountObjectType';
    const SUPPLIER                 = 'Supplier';
    const SUPPLIERINVOICE          = 'SupplierInvoice';
    const SUPPLIERINVOICES         = 'SupplierInvoices';
    const SUPPLIERS                = 'Suppliers';
    const TAG                      = 'Tag';
    const VOUCHERREFERENCE         = 'VoucherReference';

    /**
     * Sie attribute constants
     */
    const ACCOUNTID                = 'accountId';
    const ADDRESS1                 = 'address1';
    const ADDRESS2                 = 'address2';
    const AMOUNT                   = 'amount';
    const BIC                      = 'BIC';
    const BY                       = 'by';
    const BGACCOUNT                = 'BgAccount';
    const CITY                     = 'city';
    const CLIENTID                 = 'clientId';
    const CLOSED                   = 'closed';
    const COUNTRY                  = 'country';
    const CURRENCY                 = 'currency';
    const CUSTOMERID               = 'customerId';
    const DATE                     = 'date';
    const DIMID                    = 'dimId';
    const DOCUMENTID               = 'documentId';
    const DUEDATE                  = 'dueDate';
    const END                      = 'end';
    const FALSE                    = 'false';
    const FILENAME                 = 'fileName';
    const FISCALYEARID             = 'fiscalYearId';
    const HASATTACHEDVOUCHERFILES  = 'hasAttachedVoucherFiles';
    const HASLEDGERENTRIES         = 'hasLedgerEntries';
    const HASSUBORDINATEACCOUNTS   = 'hasSubordinateAccounts';
    const IBAN                     = 'IBAN';
    const ID                       = 'id';
    const INVOICENUMBER            = 'invoiceNumber';
    const JOURNALDATE              = 'journalDate';
    const JOURNALENTRYID           = 'journalEntryId';
    const JOURNALID                = 'journalId';
    const LASTCOVEREDDATE          = 'lastCoveredDate';
    const LEDGERDATE               = 'ledgerDate';
    const MONTH                    = 'month';
    const MULTIPLE                 = 'multiple';
    const NAME                     = 'name';
    const OBJECTID                 = 'objectId';
    const OCRNUMBER                = 'ocrNumber';
    const ORGANIZATIONID           = 'organizationId';
    const PGACCOUNT                = 'PgAccount';
    const PRIMARY                  = 'primary';
    const PRIMARYACCOUNTID         = 'primaryAccountId';
    const QUANTITY                 = 'quantity';
    const REFERENCEID              = 'referenceId';
    const START                    = 'start';
    const SUPPLIERID               = 'supplierId';
    const TAXONOMY                 = 'taxonomy';
    const TEXT                     = 'text';
    const TIME                     = 'time';
    const TYPE                     = 'type';
    const TRUE                     = 'true';
    const UNIT                     = 'unit';
    const URI                      = 'URI';
    const VATNR                    = 'vatNr';
    const VERSION                  = 'version';
    const ZIPCODE                  = 'zipcode';

    /**
     * Sie type attribute value constants
     */
    const ASSET                    = 'asset';
    const COST                     = 'cost';
    const EQUITY                   = 'equity';
    const INCOME                   = 'income';
    const LIABILITY                = 'liability';
    const STATISTICS               = 'statistics';

    /**
     * const Sie5 URis
     */
    const SIE5SCHEMALOCATION       = "http://www.sie.se/sie5 http://www.sie.se/sie5.xsd";
    const SIE5URI                  = "http://www.sie.se/sie5";
}
