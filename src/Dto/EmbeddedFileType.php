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
declare( strict_types = 1 );
namespace Kigkonsult\Sie5Sdk\Dto;

use InvalidArgumentException;
use Kigkonsult\Sie5Sdk\Impl\CommonFactory;

class EmbeddedFileType extends Sie5DtoBase implements DocumentsTypesInterface
{
    /**
     * @var string|null
     *
     * xsd:base64Binary
     */
    private ?string $embeddedFile = null;

    /**
     * @var int|null
     *
     * Attribute name="id" type="xsd:positiveInteger" use="required"
     */
    private ?int $id = null;

    /**
     * @var string|null
     *
     * Attribute name="fileName" type="xsd:string" use="required"
     */
    private ?string $fileName = null;

    /**
     * Factory method, set id, fileName and file content
     *
     * @param mixed $id
     * @param string $fileName
     * @param string $content
     * @return static
     */
    public static function factoryIdNameContent( mixed $id, string $fileName, string $content ) : self
    {
        return self::factory()
                   ->setId( $id )
                   ->setFileName( $fileName )
                   ->setEmbeddedFile( $content );
    }

    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( null === $this->id ) {
            $local[] = self::errMissing(self::class, self::ID );
        }
        if( null === $this->fileName ) {
            $local[] = self::errMissing(self::class, self::FILENAME );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * @return null|string
     */
    public function getEmbeddedFile() : ?string
    {
        return $this->embeddedFile;
    }

    /**
     * @param string $embeddedFile
     * @return static
     */
    public function setEmbeddedFile( string $embeddedFile ) : self
    {
        $this->embeddedFile = $embeddedFile;
        return $this;
    }

    /**
     * @return null|int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return static
     * @throws InvalidArgumentException
     */
    public function setId( mixed $id ) : self
    {
        $this->id = CommonFactory::assertPositiveInteger( $id );
        return $this;
    }

    /**
     * @return null|string
     */
    public function getFileName() : ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return static
     */
    public function setFileName( string $fileName ) : self
    {
        $this->fileName = $fileName;
        return $this;
    }
}
