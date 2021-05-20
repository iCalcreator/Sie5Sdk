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

use Kigkonsult\Sie5Sdk\Dto\SupplierType as Dto;
use Faker;

class SupplierType
{

    /**
     * @return Dto
     * @access static
     */
    public static function loadFromFaker() {
        $faker = Faker\Factory::create();

        return Dto::factoryIdName(
            (string) $faker->numberBetween( 60000, 69999 ),
            $faker->company
        )
                  ->setOrganizationId((string) $faker->numberBetween( 6000000000, 6999999999 ))
                  ->setVatNr((string) $faker->numberBetween( 6000000000, 6999999999 ))
                  ->setAddress1( $faker->streetAddress )
                  ->setAddress2( $faker->streetAddress )
                  ->setZipcode( $faker->postcode )
                  ->setCity( $faker->city )
                  ->setCountry( $faker->country)
                  ->setBgAccount((string) $faker->numberBetween( 10000000, 99999999 ))
                  ->setPgAccount((string) $faker->numberBetween( 1000000, 9999990 ))
                  ->setBic( $faker->swiftBicNumber )
                  ->setIban( $faker->iban() )
            ;
    }

}
