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
namespace Kigkonsult\Sie5Sdk\XMLWrite;

use Kigkonsult\Sie5Sdk\Dto\Sie5DtoBase;
use Kigkonsult\Sie5Sdk\Dto\Sie;
use Kigkonsult\Sie5Sdk\Dto\SieEntry;
use XMLWriter;
use InvalidArgumentException;

use function sprintf;

class Sie5Writer extends Sie5WriterBase implements Sie5WriterInterface
{
    /**
     * Write xml-string
     *
     * @param Sie5DtoBase $sie5DtoBase
     * @return string
     * @throws InvalidArgumentException
     */
    public function write( Sie5DtoBase $sie5DtoBase ) : string
    {
        static $FMTerr1 = 'Unknown xml root element %s';
        $this->writer = new XMLWriter();
        $this->writer->openMemory();
        $this->writer->setIndent( true );
        $this->writer->startDocument( '1.0', 'UTF-8' );
        switch ( true ) {
            case ( $sie5DtoBase instanceof Sie ) :
                RootSieWriter::factory( $this->writer)->write( $sie5DtoBase );
                break;
            case ( $sie5DtoBase instanceof SieEntry ) :
                RootSieEntryWriter::factory( $this->writer)->write( $sie5DtoBase );
                break;
            default :
                throw new InvalidArgumentException( sprintf( $FMTerr1, get_class( $sie5DtoBase )));
        }
        return $this->writer->outputMemory();
    }
}
