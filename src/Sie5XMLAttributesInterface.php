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

Interface Sie5XMLAttributesInterface
{
    /**
     * const XML root element attributes
     */
    const XMLNS_XSI                = 'xmlns:xsi';
    const XMLNS_XSD                = 'xmlns:xsd';
    const XSI_SCHEMALOCATION       = 'xsi:schemaLocation';
    const XMLNS                    = 'xmlns';

    /**
     * const extended Sie XML element attributes
     */
    const XSITYPE                  =  'xsi:type';

    /**
     * const XML Schema keys
     */
    const XMLSchemaKeys = [ self::XMLNS, self::XMLNS_XSI, self::XMLNS_XSD, self::XSI_SCHEMALOCATION ];

    /**
     * const XML URis
     */
    const XMLSCHEMAINSTANCE  = "http://www.w3.org/2001/XMLSchema-instance";
    const XMLSCHEMA          = "http://www.w3.org/2001/XMLSchema";

    /**
     * const XMLreader element node properties
     */
    const BASEURI                  = 'baseURI';      // The base URI of the node
    const LOCALNAME                = 'localName';    // The local name of the node
//  const NAME                     = 'name';         // The qualified name of the node -- dupl const in Sie5DtoInterface
    const NAMESPACEURI             = 'namespaceURI'; // The URI of the namespace associated with the node
    const PREFIX                   = 'prefix';       // The prefix of the namespace associated with the node
}