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

use Kigkonsult\Sie5Sdk\Sie5Interface;
use Kigkonsult\Sie5Sdk\Dto\BaseBalanceMultidimType as Dto;
use Faker;

class BaseBalanceMultidimType implements Sie5Interface
{
    /**
     * @return Dto
     */
    public static function loadFromFaker() {
        $faker = Faker\Factory::create();

        $dto = Dto::factoryMonthAmount(
            $faker->date( 'Y-m' ),
            ( $faker->numberBetween( -999999, 999999 ) / 100 )
        )
            ->setQuantity( $faker->numberBetween( 1, 9 ));
        $max = $faker->numberBetween( 2, 9 );
        for( $x = 0; $x <= $max; $x++ ) {
            if( 1 == ( $faker->numberBetween( 1, 2 ))) {
                $dto->addBaseBalanceMultidimType( self::FOREIGNCURRENCYAMOUNT, ForeignCurrencyAmountType::loadFromFaker());
            }
            $dto->addBaseBalanceMultidimType( self::OBJECTREFERENCE, ObjectReferenceType::loadFromFaker());
            $dto->addBaseBalanceMultidimType( self::OBJECTREFERENCE, ObjectReferenceType::loadFromFaker());
            if( 1 == ( $faker->numberBetween( 1, 2 ))) {
                $dto->addBaseBalanceMultidimType( self::OBJECTREFERENCE, ObjectReferenceType::loadFromFaker());
            }
        }
        return $dto;
    }
}
