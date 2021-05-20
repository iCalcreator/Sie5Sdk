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

use Kigkonsult\Sie5Sdk\Dto\CustomerInvoiceType as Dto;
use Faker;

class CustomerInvoiceType
{
    /**
     * @param mixed $id
     * @return Dto
     * @access static
     */
    public static function loadFromFaker( $id = null ) {
        $faker = Faker\Factory::create();

        if( empty( $id )) {
            $id = $faker->numberBetween( 6000000000, 6999999999 );
        }
        $dto = Dto::factoryIdDateAmount(
            $id,
            $faker->dateTimeThisYear( '+1 month' ),
            ( $faker->numberBetween( -999999, 999999 ) / 100 )
        )
                  ->setName( $faker->company )
                  ->setCustomerId((string) $faker->numberBetween( 6000000000, 6999999999 ) )
                  ->setInvoiceNumber((string) $faker->numberBetween( 6000000000, 6999999999 ))
                  ->setOcrNumber((string) $faker->numberBetween( 6000000000, 6999999999 ));
        $max  = $faker->numberBetween( 1, 3 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = BalancesType::loadFromFaker();
        }
        $dto->setBalances( $load );

        return $dto;
    }
}
