<?php
/**
 * SieSdk    PHP SDK for Sie5 export/import format
 *           based on the Sie5 (http://www.sie.se/sie5.xsd) schema
 *
 * This file is a part of Sie5Sdk.
 *
 * Copyright 2019 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * author    Kjell-Inge Gustafsson, kigkonsult
 * Link      https://kigkonsult.se
 * Version   0.95
 * License   Subject matter of licence is the software Sie5Sdk.
 *           The above copyright, link, package and version notices,
 *           this licence notice shall be included in all copies or substantial
 *           portions of the Sie5Sdk.
 *
 *           Sie5Sdk is free software: you can redistribute it and/or modify
 *           it under the terms of the GNU Lesser General Public License as published
 *           by the Free Software Foundation, either version 3 of the License,
 *           or (at your option) any later version.
 *
 *           Sie5Sdk is distributed in the hope that it will be useful,
 *           but WITHOUT ANY WARRANTY; without even the implied warranty of
 *           MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *           GNU Lesser General Public License for more details.
 *
 *           You should have received a copy of the GNU Lesser General Public License
 *           along with Sie5Sdk. If not, see <https://www.gnu.org/licenses/>.
 */
namespace Kigkonsult\Sie5Sdk;

use Kigkonsult\Sie5Sdk\DtoLoader\SieEntry2;
use Kigkonsult\Sie5Sdk\DtoLoader\SieEntry3;
use Kigkonsult\Sie5Sdk\XMLParse\Sie5Parser;
use Kigkonsult\Sie5Sdk\XMLWrite\Sie5Writer;

if( ! class_exists( 'BaseTest' )) {
    include __DIR__ . '/../autoload.php';
}

/**
 * Class TestSieEntry
 */
class TestSieEntry extends BaseTest
{

    /**
     * Create minimal sieEntry-instance loaded with Faker-data, write xml1 and parse again, write xml2 and compare
     *
     * @test
     */
    public function sieEntryTest2() {

        echo PHP_EOL . ' START  (mini) ' . __FUNCTION__ . PHP_EOL;
        $startTime = microtime( true );               // ---- load
        $sie1      = SieEntry2::loadFromFaker();
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'load time', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        $startTime = microtime( true );               // ---- write XML
        $xml1      = Sie5Writer::factory()->write( $sie1 );
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'write time 1', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        if( defined( 'SAVEXML' ) && ( false !== SAVEXML )) {
            file_put_contents( SAVEXML . DIRECTORY_SEPARATOR . __FUNCTION__ . '1.xml', $xml1 );
        }

        $startTime = microtime( true );               // ---- parse XML
        $sie2      = Sie5Parser::factory()->parse( $xml1 );
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'parse time', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        $startTime = microtime( true );               // ---- write XML again
        $xml2 = Sie5Writer::factory()->write( $sie2 );
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'write time 2', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        if( defined( 'SAVEXML' ) && ( false !== SAVEXML )) {
            file_put_contents( SAVEXML . DIRECTORY_SEPARATOR . __FUNCTION__ . '2.xml', $xml2 );
        }
        $this->assertXmlStringEqualsXmlString(
            $xml1,
            $xml2,
            sprintf( self::$CMPERR1, __FUNCTION__ )
        );
    }

    /**
     * Create full sieEntry-instance loaded with Faker-data, write xml1 and parse again, write xml2 and compare
     *
     * @test
     */
    public function sieEntryTest3() {

        echo PHP_EOL . ' START  (full) ' . __FUNCTION__ . PHP_EOL;
        $startTime = microtime( true );               // ---- load
        $sie1      = SieEntry3::loadFromFaker();
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'load time', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        foreach( self::$SieXMLAttributes as $key => $value ) {  // ---- set XML schema attrs
            $sie1->setXMLattribute( $key, $value );
        }
        $signture = $sie1->getSignature();
        if( ! empty( $signture )) {
            foreach( self::$DsigXMLAttributes as $key => $value ) { // ---- set signature XML
                $signture->setXMLattribute( $key, $value );
            }
            $sie1->setSignature( $signture );
        }

        $startTime = microtime( true );               // ---- validate 1
        $this->assertTrue(
            $sie1->isValid( $expected ),
            __FUNCTION__ . ' not valid 1' . PHP_EOL . var_export( $expected, true ) . PHP_EOL
        );
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'isValid 1', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        $startTime = microtime( true );               // ---- write XML
        $xml1      = Sie5Writer::factory()->write( $sie1 );
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'write time 1', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        if( defined( 'SAVEXML' ) && ( false !== SAVEXML )) {
            file_put_contents( SAVEXML . DIRECTORY_SEPARATOR . __FUNCTION__ . '1.xml', $xml1 );
        }

        $startTime = microtime( true );               // ---- parse XML
        $sie2 = Sie5Parser::factory()->parse( $xml1 );
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'parse time', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        $startTime = microtime( true );               // ---- validate 2
        $this->assertTrue(
            $sie2->isValid( $expected ),
            __FUNCTION__ . ' not valid 2' . PHP_EOL . var_export( $expected, true ) . PHP_EOL
        );
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'isValid 2', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        echo 'accountIds       : ', implode( ',', $sie2->getAllAccountIds()), PHP_EOL;
        echo 'DimensionIds     : ', implode( ',', $sie2->getAllDimensionIds()) .  PHP_EOL;
        echo 'CustomerIds      : ', var_export( $sie2->getAllCustomerInvoicesCustomerIds(), true ),  PHP_EOL;
        echo 'SupplierIds      : ', var_export( $sie2->getAllSupplierInvoicesSupplierIds(), true ),  PHP_EOL;
        echo 'JournalLedgerAccountIds : ', var_export( $sie2->getAllJournalEntryLedgerEntryAccountIds(), true ), PHP_EOL;
        if( ! $sie2->hasBalancedJournalLedgerEntries( $errorIx )) {
            echo 'not balanced : ', var_export( $errorIx, true ), PHP_EOL;
        }
        echo 'VoucherDocIds()  : ', var_export( $sie2->getAllJournalEntryVoucherReferenceDocumentIds(), true ), PHP_EOL;
        echo 'DocumentIds()    : ', implode( ',', $sie2->getAllDocumentsTypeIds()), PHP_EOL;

        $startTime = microtime( true );               // ---- write XML again
        $xml2      = Sie5Writer::factory()->write( $sie2 );
        echo sprintf(
            self::$FMTTIME,
            __FUNCTION__,
            str_pad( 'write time 2', 12 ),
            ( microtime( true ) - $startTime ),
            PHP_EOL
        );

        if( defined( 'SAVEXML' ) && ( false !== SAVEXML )) {
            file_put_contents( SAVEXML . DIRECTORY_SEPARATOR . __FUNCTION__ . '2.xml', $xml2 );
        }
        $this->assertXmlStringEqualsXmlString(
            $xml1,
            $xml2,
            sprintf( self::$CMPERR1, __FUNCTION__ )
        );
    }

    /**
     * @ . test
     */
    public function sieEntryTest9() {
        /*
        static $nodeTypes = [
            0  => 'NONE',
            1  => 'ELEMENT',
            2  => 'ATTRIBUTE',
            3  => 'TEXT',
            4  => 'CDATA',
            5  => 'ENTITY_REF',
            6  => 'ENTITY',
            7  => 'PI',
            8  => 'COMMENT',
            9  => 'DOC',
            10 => 'DOC_TYPE',
            11 => 'DOC_FRAGMENT',
            12 => 'NOTATION',
            13 => 'WHITESPACE',
            14 => 'SIGNIFICANT_WHITESPACE',
            15 => 'END_ELEMENT',
            16 => 'END_ENTITY',
            17 => 'XML_DECLARATION',
        ];

        $opts = LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NSCLEAN | LIBXML_HTML_NODEFDTD;
        $testfile = './docs/SIE5/Sample_files/Sample.sie';
        $logger = LoggerDepot::getLogger( __NAMESPACE__ );

        $reader  = new XMLReader();
        $reader->xml( file_get_contents( $testfile ), null, $opts );
        while( $reader->read()) {
            $logger->debug( 'type: ' . $nodeTypes[$reader->nodeType] . ', name: ' . $reader->localName );
            if( $reader->hasAttributes ) {
                while( $reader->moveToNextAttribute()) {
                    $logger->debug( ' Found attribute ' . $reader->localName . ' - ' . $reader->value );
                } // end while
                $reader->moveToElement();
            }
        }
        $reader->close();
        */
        $this->assertTrue( true );
    }
}
