<?php
/**
 * Sie5Sdk    PHP SDK for Sie5 export/import format
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

use Kigkonsult\Sie5Sdk\Dto\ObjectType as Dto;
use Faker;

class ObjectType
{

    /**
     * @return Dto
     * @access static
     */
    public static function loadFromFaker() : Dto
    {

        $faker = Faker\Factory::create();

        static $words = [];
        static $ids   = [];
        $word = $faker->word;
        while( in_array( $word, $words, true ) ) {
            $word = $faker->word;
        }
        $words[] = $word;

        $id = (string) $faker->numberBetween( 1000, 9999 );
        while( in_array( $id, $ids, true ) )  {
            $id = (string) $faker->numberBetween( 1000, 9999 );
        }
        $ids[] = $id;

        return Dto::factoryIdName(
            ( 1 === $faker->numberBetween( 1, 2 ) ? $id : $word ),
            $faker->word
        );
    }

}
