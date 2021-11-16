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

use Kigkonsult\Sie5Sdk\Sie5Interface;
use Kigkonsult\Sie5Sdk\Dto\LedgerEntryType as Dto;
use Faker;

class LedgerEntryType implements Sie5Interface
{
    /**
     * @param float $amount
     * @return Dto
     * @access static
     */
    public static function loadFromFaker( float $amount ) : Dto
    {
        $faker = Faker\Factory::create();

        $dto = Dto::factoryAccountIdAmount(
            (string) $faker->numberBetween( 1000, 9999 ),
            $amount,
            $faker->numberBetween( 1, 5 )
        )
                  ->setText((string) $faker->sentences( 5, true ))
                  ->setLedgerDate( $faker->dateTimeThisMonth());

        $load = [];
        if( 1 === $faker->numberBetween( 1, 2 )) {
            $load[] = [
                self::FOREIGNCURRENCYAMOUNT =>
                    ForeignCurrencyAmountType::loadFromFaker(),
            ];
        }
        $max2 = $faker->numberBetween( 0, 3 );
        for( $x2 = 0; $x2 < $max2; $x2++ ) {
            $load[] = [
                self::OBJECTREFERENCE =>
                    ObjectReferenceType::loadFromFaker(),
            ];
        }
        if( 1 === $faker->numberBetween( 1, 2 )) {
            $load[] = [
                self::SUBDIVIDEDACCOUNTOBJECTREFERENCE =>
                    SubdividedAccountObjectReferenceType::loadFromFaker(),
            ];
        }
        if( 1 === $faker->numberBetween( 1, 2 )) {
            $load[] = [
                self::ENTRYINFO =>
                    EntryInfoType::loadFromFaker(),
            ];
        }
        if( 1 === $faker->numberBetween( 1, 2 )) {
            $load[] = [
                self::OVERSTRIKE =>
                    OverstrikeType::loadFromFaker(),
            ];
        }
        if( 1 === $faker->numberBetween( 1, 2 )) {
            $load[] = [
                self::LOCKINGINFO =>
                    LockingInfoType::loadFromFaker(),
            ];
        }
        $dto->setLedgerEntryTypes( $load );
        return $dto;
    }
}
