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
use TypeError;

use function array_keys;
use function current;
use function get_class;
use function is_array;
use function is_null;
use function key;
use function reset;
use function sprintf;

class DocumentsType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var array
     *
     * Sets  of  1-x EmbeddedFileType OR single FileReferenceType
     * Individual source document
     */
    private $documentsTypes  = [];

    /**
     * @var string
     */
    private $previousElement = null;

    /**
     * @var int
     */
    private $elementSetIndex = 0;

    /**
     * Return bool true is instance is valid
     *
     * @param array $outSide
     * @return bool
     */
    public function isValid( array & $outSide = null ) : bool
    {
        $local  = [];
        $inside = [];
        foreach( array_keys( $this->documentsTypes ) as $x1 ) { // elementSet x1
            $inside[$x1] = [];
            foreach( array_keys( $this->documentsTypes[$x1] ) as $x2 ) { // keyed element x2
                $inside[$x1][$x2] = [];
                reset( $this->documentsTypes[$x1][$x2] );
                $key    = key( $this->documentsTypes[$x1][$x2] );
                if( $this->documentsTypes[$x1][$x2][$key]->isValid(
                    $inside[$x1][$x2] )
                ) {
                    unset( $inside[$x1][$x2] );
                }
            } // end foreach
            if( empty( $inside[$x1] )) {
                unset( $inside[$x1] );
            }
        } // end foreach
        if( ! empty( $inside )) {
            $key         = self::getClassPropStr( self::class, self::DOCUMENTS );
            $local[$key] = $inside;
        } // end if
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single (typed) DocumentsTypesInterface
     *
     * key : self::EMBEDDEDFILE / self::FILEREFERENCE
     *
     * @param string $key
     * @param DocumentsTypesInterface $documentsType
     * @return static
     * @throws InvalidArgumentException
     */
    public function addDocumentsType(
        string $key,
        DocumentsTypesInterface $documentsType
    ) : DocumentsType
    {
        switch( true ) {
            case (( self::EMBEDDEDFILE == $key ) &&
                ( $documentsType instanceof EmbeddedFileType )) :
                break;
            case (( self::FILEREFERENCE == $key ) &&
                ( $documentsType instanceof FileReferenceType )) :
                break;
            default :
                throw new InvalidArgumentException(
                    sprintf( self::$FMTERR5, self::DOCUMENTS, $key, get_class( $documentsType ))
                );
        } // end switch
        if( $documentsType->isValid() &&
            ( true !== $this->isDocumentIdUnique( $documentsType->getId()))) {
            throw new InvalidArgumentException(
                sprintf( self::$FMTERR11, self::DOCUMENTS, self::ID, $documentsType->getId())
            );
        }
        if( self::FILEREFERENCE == $this->previousElement ) {
            $this->elementSetIndex += 1;
        }
        $this->documentsTypes[$this->elementSetIndex][] = [ $key => $documentsType ];
        $this->previousElement = $key;
        return $this;
    }

    /**
     * Return EmbeddedFileType|FileReferenceType if id given, (bool false on not found) otherwise array all
     *
     * @param int $id
     * @return array|DocumentsTypesInterface|bool
     */
    public function getDocumentsTypes( int $id = null )
    {
        if( ! is_null( $id )) {
            foreach( array_keys( $this->documentsTypes ) as $ix1 ) { // elementSet ix
                foreach( array_keys( $this->documentsTypes[$ix1] ) as $ix2 ) { // element ix
                    foreach( $this->documentsTypes[$ix1][$ix2] as $element ) { // element
                        if( $id == $element->getId()) {
                            return $element;
                        }
                    } // end foreach
                } // end foreach
            } // end foreach
            return false;
        }
        return $this->documentsTypes;
    }

    /**
     * Return array with all dokumentType ids
     *
     * @return array
     */
    public function getAllDocumentsTypeIds() : array
    {
        $documentsTypeIds = [];
        foreach( array_keys( $this->documentsTypes ) as $ix1 ) { // elementSet ix
            foreach( array_keys( $this->documentsTypes[$ix1] ) as $ix2 ) { // element ix
                foreach( $this->documentsTypes[$ix1][$ix2] as $element ) { // element
                    $documentsTypeIds[] = $element->getId();
                } // end foreach
            } // end foreach
        } // end foreach
        return $documentsTypeIds;
    }

    /**
     * Return bool true if Document id is set
     *
     * @param int $id
     * @return bool  true on found, false if not
     */
    public function isDocumentIdUnique( int $id ) : bool
    {
        return ( false === array_search( $id, $this->getAllDocumentsTypeIds()));
    }

    /**
     * Set DocumentsTypes, array, *EmbeddedFileType/FileReferenceType OR *[ type => EmbeddedFileType/FileReferenceType ]
     *
     * Type : self::EMBEDDEDFILE / self::FILEREFERENCE
     *
     * @param array $documentsTypes
     * @return static
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function setDocumentsTypes( array $documentsTypes ) : self
    {
        foreach( $documentsTypes as $ix1 => $elementSet ) {
            if( ! is_array( $elementSet )) {
                $elementSet = [ $ix1 => $elementSet ];
            }
            foreach( $elementSet as $ix2 => $element ) {
                switch( true ) {
                    case is_array( $element ) :
                        break;
                    case ( $element instanceof EmbeddedFileType ) :
                        $element = [ self::EMBEDDEDFILE => $element ];
                        break;
                    case ( $element instanceof FileReferenceType ) :
                        $element = [ self::FILEREFERENCE => $element ];
                        break;
                    default :
                        $element = [ $ix2 => $element ];
                } // end switch
                reset( $element );
                $key = key( $element );
                $this->addDocumentsType( $key, current( $element ));
            } // end foreach
        } // end foreach
        return $this;
    }
}
