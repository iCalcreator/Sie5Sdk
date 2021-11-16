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
namespace Kigkonsult\Sie5Sdk\DtoLoader;

use Kigkonsult\Sie5Sdk\Dto\SieEntry as Dto;
use Faker;
use Kigkonsult\DsigSdk\DsigLoader\SignatureType1 as SignatureType;

/**
 * Class SieEntry
 *
 * Root element for entry file
 */
class SieEntry
{
    /**
     * @return Dto
     * @access static
     */
    public static function loadFromFaker() : Dto
    {
        $faker = Faker\Factory::create();

        // 3 unique docIds
        static $used = [];
        $docIds      = [];
        while( 3 > count( $docIds )) {
            $docId = $faker->numberBetween( 1000, 9999 );
            while (isset($used[$docId])) {
                $docId = $faker->numberBetween( 1000, 9999 );
            }
            $used[$docId] = $docId;
            $docIds[]     = $docId;
        } // end while

        $dto = Dto::factory()
                  ->setFileInfo( FileInfoTypeEntry::loadFromFaker())
                  ->setAccounts( AccountsTypeEntry::loadFromFaker() )
                  ->setDimensions( DimensionsTypeEntry::loadFromFaker() )
                  ->setCustomers( CustomersType::loadFromFaker() )
                  ->setSuppliers( SuppliersType::loadFromFaker() )
                  ->setDocuments( DocumentsType::loadFromFaker( $docIds ))
                  ->setSignature( SignatureType::loadFromFaker());

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = CustomerInvoicesTypeEntry::loadFromFaker();
        }
        $dto->setCustomerInvoices( $load );

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = SupplierInvoicesTypeEntry::loadFromFaker();
        }
        $dto->setSupplierInvoices( $load );

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = FixedAssetsTypeEntry::loadFromFaker();
        }
        $dto->setFixedAssets( $load );

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = GeneralSubdividedAccountTypeEntry::loadFromFaker();
        }
        $dto->setGeneralSubdividedAccount( $load );

        $max  = $faker->numberBetween( 2, 8 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = JournalTypeEntry::loadFromFaker();
        }
        $dto->setJournal( $load );

        return $dto;
    }
}
