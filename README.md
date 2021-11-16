## Sie5Sdk

- the PHP SDK for the [Sie]5 export/import formats
- manages accounting, book-keeping, ledger, asset, inventory data etc 
- based on the Sie5 [XSD] schema

and provide

* [src/Dto](src/Dto) for all element(/complexType)s in [XSD]
  * with getters and validating setters
  * ability to validate comparable to [XSD]
  * minor other logic
* parse of the XML rootelements Sie/SieEntry into dto(s)
  * [src/XMLParse/Sie5Parser::parse](src/XMLParse/Sie5Parser.php)
* write of Sie/SieEntry dto(s) to XML string / DomNode
  * [src/XMLWrite/Sie5Writer:write](src/XMLWrite/Sie5Writer.php)

#### Usage, parse XML
Sie5Sdk uses XMLReader parsing input 
and accepts Sie and SieEntry root elements.

To parse an export Sie XML file :

```php
<?php
namespace Kigkonsult\Sie5Sdk;
use Kigkonsult\Sie5Sdk\XMLParse\Sie5Parser;

$sie = Sie5Parser::factory()->parse( 
    file_get_contents( 'SieExportFile.xml' )
);

foreach( $sie->getAccounts()->getAccount() as $account ) {
    $id = $account->getId();
    //...
}
//...
```
To parse an import SieEntry XML file :

```php
<?php
namespace Kigkonsult\Sie5Sdk;
use Kigkonsult\Sie5Sdk\XMLParse\Sie5Parser;

$sieEntry = Sie5Parser::factory()->parse( 
    file_get_contents( 'SieImportFile.xml' )
);

foreach( $sieEntry->getAccounts()->getAccount() as $account ) {
    $id = $account->getId();
    //...
}
//...
```
The XML parser save the XMLreader node properties (baseURI, localName, name, namespaceURI, prefix)
for each XML (Dto) element as 'XMLattributes' as well as XML attributes (xmlns, xmlns:*, schemaLocation), if set.


#### Usage, build up structure
 
To build up import SieEntry structure:

```php
<?php
namespace Kigkonsult\Sie5Sdk;
use Kigkonsult\Sie5Sdk\XMLWrite\Sie5Writer;
use Kigkonsult\Sie5Sdk\Dto\SieEntry;
use Kigkonsult\Sie5Sdk\Dto\AccountsTypeEntry;
use Kigkonsult\Sie5Sdk\Dto\AccountTypeEntry;

$sieEntry = sieEntry::factory()
    ->setXMLattribute( SieEntry::XMLNS_XSI,          SieEntry::XMLSCHEMAINSTANCE )
    ->setXMLattribute( SieEntry::XMLNS_XSD,          SieEntry::XMLSCHEMA )
    ->setXMLattribute( SieEntry::XSI_SCHEMALOCATION, SieEntry::SIE5SCHEMALOCATION )
    ->setXMLattribute( SieEntry::XMLNS,              SieEntry::SIE5URI );

    ->setAccounts( 
        AccountsTypeEntry::factory()
            ->setAccount(
                [
                    AccountTypeEntry::factory()
                        ->setId( '1910' )
                        ->setName( 'Kassa' )
                        ->setType( AccountTypeEntry::ASSET ),
                    AccountTypeEntry::factory()
                        ->setId( '1930' )
                        ->setName( 'Bank' )
                        ->setType( AccountTypeEntry::ASSET )
                ]
            )
    )
    ->set...
    
$XMLstring = Sie5Writer::factory()->write( $sieEntry );
...
```


###### XML attributes

You may also set XMLattribute(s) (explicitly, NOT required if rewriting a parsed sie xml file) using 

```php
$sieEntry->setXMLAttribut( <key>, <value> );
```
To set (ex. prefix) and 'propagate' down in hierarchy:
```php
$sieEntry->setXMLAttribut( SieEntry::PREFIX, <value>, true );
```
You can remove (single 'element') XMLattribute using
```php
$dsig->unsetXMLAttribut( <key> );
```
To unset (ex. prefix) and 'propagate' down in hierarchy:
```php
$sieEntry->unsetXMLAttribut( SignatureType::PREFIX, true );
```
To fetch and iterate over XMLAttributes 
```php
foreach( $sieEntry->getXMLAttributes() as $key => $value {
    ...
}
```
#### Usage, output as XML
Sie5Sdk uses XMLWriter creating output.

```php
$XMLstring = Sie5Writer::factory()->write( $sieEntry );
```
The XMLwriter adds for (each) element
  * element name with prefix, is exists
  * XMLattribute xmlns, xmlns:* and schemaLocation, if exists.

#### Usage, output as DomNode
```php
$domNode = Sie5Writer::factory()->write( $sieEntry, true );
```

#### Info

Sie5Sdk require PHP7+.

For class structure, architecture and usage, please examine 
* the [XSD]
* [docs/info.txt](docs/info.txt)
* [docs/Sie5Sdk.png](docs/Sie5Sdk.png) dto class design
* [test/DtoLoader](test/DtoLoader) directory

You may find convenient constants in 
- [src/Sie5Interface](src/Sie5Interface.php)
- [src/Sie5XMLAttributesInterface](src/Sie5XMLAttributesInterface.php)

Sie5Sdk uses
* kigkonsult\\[DsigSdk]
  * for the [Signature] part
* kigkonsult\\[loggerdepot] and [Psr\Log]
  * for (parser-)logging

Sie5Sdk support **attribute** extensions in the elements
* Account, Company, CustomerInvoice, Customer, FileInfo, FixedAsset, GeneralObject, JournalEntry, LedgerEntry, Supplier 

#### Installation

[Composer], from the Command Line:

``` php
composer require kigkonsult/sie5sdk
```

[Composer], in your `composer.json`:

``` json
{
    "require": {
        "kigkonsult/sie5sdk": "dev-master"
    }
}
```

Version 1.2 supports PHP 7.4, 1.1 7.0.


Acquire access
``` php
namespace Kigkonsult\Sie5Sdk;
...
include 'vendor/autoload.php';
```


Otherwise , download and acquire..

``` php
namespace Kigkonsult\Sie5Sdk;
...
include 'pathToSource/sie5sdk/autoload.php';
```


#### Sponsorship
Donation using [paypal.me/kigkonsult] are appreciated.
For invoice, please [e-mail]</a>.


#### Support

For support, please use [Github]/issues.

For Sie [XSD] issues, go to [Sie] homepage. 


#### License

This project is licensed under the LGPLv3 License


[Composer]:https://getcomposer.org/
[DsigSdk]:https://github.com/iCalcreator/dsigsdk
[e-mail]:mailto:ical@kigkonsult.se
[Github]:https://github.com/iCalcreator/sie5sdk/issues
[loggerdepot]:https://github.com/iCalcreator/loggerdepot
[paypal.me/kigkonsult]:https://paypal.me/kigkonsult
[Psr\Log]:https://github.com/php-fig/log
[Sie]:http://www.sie.se
[Signature]:https://www.w3.org/TR/2002/REC-xmldsig-core-20020212/xmldsig-core-schema.xsd
[XSD]:http://www.sie.se/sie5.xsd
