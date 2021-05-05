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
 * @version   0.95
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
namespace Kigkonsult\Sie5Sdk\DtoLoader;

use Faker;
use Kigkonsult\DsigSdk\DsigLoader\SignatureType1 as SignatureType;
use Kigkonsult\Sie5Sdk\Dto\SieEntry as Dto;

/**
 * Class SieEntry3
 *
 * Root element for entry file
 */
class SieEntry3
{

    /**
     * @return Dto
     * @access static
     */
    public static function loadFromFaker() {
        $faker = Faker\Factory::create();

        $dto = Dto::factory()
                  ->setFileInfo( FileInfoTypeEntry::loadFromFaker())
                  ->setAccounts( AccountsTypeEntry::loadFromFaker() )
                  ->setDimensions( DimensionsTypeEntry::loadFromFaker() )
                  ->setCustomers( CustomersType::loadFromFaker() )
                  ->setSuppliers( SuppliersType::loadFromFaker() )
                  ->setSignature( SignatureType::loadFromFaker());

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = CustomerInvoicesTypeEntry::loadFromFaker();
        }
        $dto->setCustomerInvoices( $load );
        $dto->addCustomerInvoices( CustomerInvoicesTypeEntry::loadFromFaker());

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = SupplierInvoicesTypeEntry::loadFromFaker();
        }
        $dto->setSupplierInvoices( $load );
        $dto->addSupplierInvoices( SupplierInvoicesTypeEntry::loadFromFaker());

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = FixedAssetsTypeEntry::loadFromFaker();
        }
        $dto->setFixedAssets( $load );
        $dto->addFixedAsset( FixedAssetsTypeEntry::loadFromFaker());

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = GeneralSubdividedAccountTypeEntry::loadFromFaker();
        }
        $dto->setGeneralSubdividedAccount( $load );
        $dto->addGeneralSubdividedAccount( GeneralSubdividedAccountTypeEntry::loadFromFaker());

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = JournalTypeEntry::loadFromFaker();
        }
        $dto->setJournal( $load );
        $dto->addJournal( JournalTypeEntry::loadFromFaker());

        $docIds = [];
        foreach( $dto->getAllJournalEntryVoucherReferenceDocumentIds() as $journals ) {
            foreach( $journals as $entries ) {
                foreach( $entries as $docId ) {
                    $docIds[$docId] = $docId; // assure uniqueness
                }
            }
        }
        $dto->setDocuments( DocumentsType::loadFromFaker( $docIds ));
        return $dto;
    }

}
