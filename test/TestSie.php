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
namespace Kigkonsult\Sie5Sdk;

use DOMNode;
use Kigkonsult\Sie5Sdk\Dto\Sie;
use Kigkonsult\Sie5Sdk\DtoLoader\Sie2;
use Kigkonsult\Sie5Sdk\DtoLoader\Sie3;
use Kigkonsult\Sie5Sdk\XMLParse\Sie5Parser;
use Kigkonsult\Sie5Sdk\XMLWrite\Sie5Writer;

if( ! class_exists( 'BaseTest' )) {
    include __DIR__ . '/../autoload.php';
}

/**
 * Class TestSie
 */
class TestSie extends BaseTest
{


    /**
     * Create minimal sie-instance loaded with Faker-data, write xml1 and parse again, write xml2 and compare
     *
     * @test
     */
    public function sieTest2mini() : void
    {

        echo PHP_EOL . ' START  (mini) ' . __FUNCTION__ . PHP_EOL;
        $startTime = microtime( true );               // ---- load
        $sie1      = Sie2::loadFromFaker();
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

        $signature = $sie1->getSignature();
        foreach( self::$DsigXMLAttributes as $key => $value ) { // ---- set signature XML
            $signature->setXMLattribute( $key, $value );
        }
        $sie1->setSignature( $signature );

        $startTime = microtime( true );               // ---- write to XML
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
        //echo 'XML attr then ' . var_export( $sie1->getXMLattributes(), true ) . PHP_EOL; // test ###

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

        /* will not work... TypeError...
        $this->assertXmlStringEqualsXmlString(
            $xml1,
            $xml2,
            sprintf( self::$CMPERR1, __FUNCTION__ )
        );
        */
        $this->assertSame(
            $xml1,
            $xml2,
            sprintf( self::$CMPERR1, __FUNCTION__ )
        );
    }

    /**
     * Create full sie-instance loaded with Faker-data, write xml1 and parse again, write xml2 and compare
     *
     * @test
     */
    public function sieTest3full() : void
    {

        echo PHP_EOL . ' START  (full) ' . __FUNCTION__ . PHP_EOL;
        $startTime = microtime( true );               // ---- load
        $sie1      = Sie3::loadFromFaker();
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
        $signature = $sie1->getSignature();
        foreach( self::$DsigXMLAttributes as $key => $value ) {
            $signature->setXMLattribute( $key, $value );
        }
        $sie1->setSignature( $signature );

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
        $sie2      = Sie5Parser::factory()->parse( $xml1 );
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

        $accountIds = $sie2->getAllAccountIds();
        echo 'accountIds       : ', implode( ',', $accountIds), PHP_EOL;
        $endAccountId = end( $accountIds );
        $this->assertNotTrue(
            $sie2->isAccountIdUnique( $endAccountId ),
            'accountId ' . $endAccountId . ' is NOT set ?!'
        );

        $dimensionIds = $sie2->getAllDimensionIds();
        echo 'DimensionIds     : ', implode( ',', $dimensionIds ) .  PHP_EOL;
        $endDimensionId = end( $dimensionIds );
        $this->assertNotTrue(
            $sie2->isDimensionsIdUnique( $endDimensionId ),
            'dimensionId ' . $endDimensionId . ' is NOT set ?!'
        );
        foreach( $sie2->getDimensions() as $dimensionsType ) {
            foreach( $dimensionsType->getDimension() as $dimensionType ) {
                $objectIds = $dimensionsType->getAllObjectIds();
                $endId     = end( $objectIds );
                $this->assertNotTrue(
                    $$dimensionsType->isObjectIdUnique( $endId ),
                    'For dimensionId ' . $dimensionType->getId() . ' ObjectId ' . $endId . ' is NOT set ?!'
                );
            } // end foreach
        } // end foreach

        echo 'CustomerIds      : ', var_export( $sie2->getAllCustomerInvoicesCustomerIds(), true ),  PHP_EOL;
        echo 'SupplierIds      : ', var_export( $sie2->getAllSupplierInvoicesSupplierIds(), true ),  PHP_EOL;
        echo 'JournalLedgerAccountIds : ', var_export( $sie2->getAllJournalEntryLedgerEntryAccountIds(), true ), PHP_EOL;
        $errorIx = [];
        if( ! $sie2->hasBalancedJournalLedgerEntries( $errorIx )) {
            echo 'not balanced : ', var_export( $errorIx, true ), PHP_EOL;
        }
        echo 'VoucherDocIds()  : ', var_export( $sie2->getAllJournalEntryVoucherReferenceDocumentIds(), true ), PHP_EOL;

        $documentsIds = $sie2->getAllDocumentsTypeIds();
        echo 'DocumentIds()    : ', implode( ',', $documentsIds ), PHP_EOL;
        $endDocId     = end( $documentsIds );
        $this->assertFalse(
            $sie2->isDocumentIdUnique( $endDocId ),
            'documentId ' . $endDocId . ' is NOT set ?!'
        );

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

        $this->assertSame(
            $xml1,
            $xml2,
            sprintf( self::$CMPERR1, __FUNCTION__ )
        );
        /* will not work... TypeError...
        $this->assertXmlStringEqualsXmlString(
            $xml1,
            $xml2,
            sprintf( self::$CMPERR1, __FUNCTION__ )

        );
        */
        $this->assertSame(
            $xml1,
            $xml2,
            sprintf( self::$CMPERR1, __FUNCTION__ )
        );
    }

    /**
     * Same as sieTest2mini but with prefix set
     *
     * @test
     */
    public function sieTest5mini() : void
    {

        echo PHP_EOL . ' START (mini+prefix) ' . __FUNCTION__ . PHP_EOL;
        $startTime = microtime( true );               // ---- load
        $sie1      = Sie2::loadFromFaker();
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
        $signature = $sie1->getSignature();
        foreach( self::$DsigXMLAttributes as $key => $value ) {
            $signature->setXMLattribute( $key, $value );
        }
        $sie1->setSignature( $signature );

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

        // real test starts here
        $signature = $sie2->getSignature();

        // $XMLattributes = $signature->getXMLattributes();
        // echo 'XML attr before ' . var_export( $XMLattributes, true ) . PHP_EOL; // test ###

        $signature->unsetXMLattribute( Sie::XMLNS, true );      // ---- set XML schema attrs
        $XMLnsDenom    = Sie::XMLNS . ':dsig';
        $signature->setXMLattribute( Sie::PREFIX, 'dsig', true );
        $signature->setXMLattribute( $XMLnsDenom, self::$DsigXMLAttributes[Sie::XMLNS], false );

        $XMLattributes = $signature->getXMLattributes();
        $this->assertFalse( isset( $XMLattributes[Sie::XMLNS] ));
        $this->assertTrue( isset( $XMLattributes[$XMLnsDenom] ));
        // echo 'XML attr after ' . var_export( $XMLattributes, true ) . PHP_EOL; // test ###

        $sie2->setSignature( $signature );
        // real test ends here

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
        $this->assertTrue(
            2 < substr_count( $xml2, 'dsig:' ),
            'Missing XMLNS denom propagated down.'

        );

    }

    /**
     * Same as sieTest2mini but output as DomNode
     *
     * @test
     */
    public function sieTest6mini() : void
    {

        echo PHP_EOL . ' START (mini+DomNode) ' . __FUNCTION__ . PHP_EOL;
        $sie = Sie2::loadFromFaker();

        $xml = Sie5Writer::factory()->write( $sie );
        if( defined( 'SAVEXML' ) && ( false !== SAVEXML )) {
            file_put_contents( SAVEXML . DIRECTORY_SEPARATOR . __FUNCTION__ . '1.xml', $xml );
        }

        $domNode = Sie5Parser::factory()->parse( $xml, true );

        $this->assertInstanceOf( DOMNode::class, $domNode );

    }

    /**
     * @ . test
     */
    /*
    public function sieTest9() {
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
        $this->assertTrue( true );
    }
    */
}
