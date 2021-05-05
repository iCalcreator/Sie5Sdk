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
namespace Kigkonsult\Sie5Sdk;

use DirectoryIterator;
use Kigkonsult\Sie5Sdk\XMLParse\Sie5Parser;
use Kigkonsult\Sie5Sdk\XMLWrite\Sie5Writer;
use Exception;
use InvalidArgumentException;
use RuntimeException;

if( ! class_exists( 'BaseTest' )) {
    include __DIR__ . '/../autoload.php';
}

/**
 * Class TestFiles
 */
class TestFiles extends BaseTest
{

    /**
     * sieFiles dataProvider
     * @return array
     */
    public function sieFilesProvider() {

        $testPath = parent::getBasePath() . 'test/files';
        $dir      = new DirectoryIterator( $testPath );
        $dataArr  = [];

        $case     = 1;
        foreach( $dir as $file ) {
            if( ! $file->isFile() ) {
                continue;
            }
            $dataArr[] =
                [
                    $case++,
                    $file->getPathname(),
                ];
        }

        return $dataArr;
    }

    /**
     * Reading xml from sample files, parse and write xml and compare
     *
     * Expects error due to attributes with default value
     *
     * @test
     * @dataProvider sieFilesProvider
     * @param int $case
     * @param string $fileName
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Exception
     */
    public function sieFiles( $case, $fileName ) {
        static $FMT0 = '%s START %s on \'%s\'%s';
        static $FMT1 = '%s \'%s\' not valid%s%s%s';
        static $FMT2 = 'Failed asserting that two Sie documents (#%d, %s) are equal.';

        echo sprintf( $FMT0, PHP_EOL, __FUNCTION__, basename( $fileName ), PHP_EOL );

        $sie = Sie5Parser::factory()->parseXmlFromFile( $fileName );

        $expected = [];
        $this->assertTrue(         // ---- validate
            $sie->isValid( $expected ),
            sprintf( $FMT1, __FUNCTION__, $fileName, PHP_EOL, var_export( $expected, true ), PHP_EOL )
        );

        /*
         * @todo 100% equal...
        */
        $xml = Sie5Writer::factory()->write( $sie );

        $this->assertXmlStringEqualsXmlString(
            file_get_contents( $fileName ),
            $xml,
            sprintf( $FMT2, $case, basename( $fileName ))
        );

    }

}
