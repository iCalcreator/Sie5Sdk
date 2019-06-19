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
namespace Kigkonsult\Sie5Sdk\Dto;

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

class EmbeddedFileType extends Sie5DtoBase implements DocumentsTypesInterface
{

    /**
     * @var string - xsd:base64Binary
     * @access private
     */
    private $embeddedFile = null;

    /**
     * @var int
     *         attribute name="id" type="xsd:positiveInteger" use="required"
     * @access private
     */
    private $id = null;

    /**
     * @var string
     *            attribute name="fileName" type="xsd:string" use="required"
     * @access private
     */
    private $fileName = null;



    /**
     * Return bool true is instance is valid
     *
     * @param array $expected
     * @return bool
     */
    public function isValid( array & $expected = null ) {
        $local = [];
        if( empty( $this->id )) {
            $local[self::ID] = false;
        }
        if( empty( $this->fileName )) {
            $local[self::FILENAME] = false;
        }
        if( ! empty( $local )) {
            $expected[self::EMBEDDEDFILE] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getEmbeddedFile() {
        return $this->embeddedFile;
    }

    /**
     * @param string $embeddedFile
     * @return static
     * @throws InvalidArgumentException
     */
    public function setEmbeddedFile( $embeddedFile ) {
        $this->embeddedFile = CommonFactory::assertString( $embeddedFile );
        return $this;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return static
     * @throws InvalidArgumentException
     */
    public function setId( $id ) {
        $this->id = CommonFactory::assertPositiveInteger( $id );
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName() {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return static
     * @throws InvalidArgumentException
     */
    public function setFileName( $fileName ) {
        $this->fileName = CommonFactory::assertString( $fileName );
        return $this;
    }

}