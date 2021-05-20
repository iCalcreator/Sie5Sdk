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
 * @version   1.0
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
declare( strict_types = 1 );
namespace Kigkonsult\Sie5Sdk\Impl;

use InvalidArgumentException;

use function checkdate;
use function clearstatcache;
use function ctype_digit;
use function ctype_upper;
use function implode;
use function in_array;
use function is_file;
use function is_bool;
use function is_numeric;
use function is_readable;
use function is_scalar;
use function is_string;
use function sprintf;
use function strlen;
use function strtolower;
use function substr;

class CommonFactory
{
    /**
     * @var string
     */
    public static $FMT1 = ' (argument #%d)';
    public static $FMT2 = '%s expected%s, \'%s\' given.';

    /**
     * Assert data is an AccountNumber and return string
     *
     * @param int|string $data
     * @param int   $argIx
     * @return string
     * @throws InvalidArgumentException
     * @todo assert pattern "[0-9]+" ?
     */
    public static function assertAccountNumber( $data, int $argIx = null ) : string
    {
        static $SUBJECT = 'AccountNumber';
        if( ctype_digit((string) $data )) {
            return (string) $data;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( self::$FMT2, $SUBJECT, $argNoFmt, $data ));
    }

    /**
     * Assert data is an Amount and return string
     *
     * @param mixed $data
     * @param int   $argIx
     * @return float
     * @throws InvalidArgumentException
     */
    public static function assertAmount( $data, int $argIx = null ) : float
    {
        static $SUBJECT = 'Amount';
        if( ctype_digit((string) $data ) || is_numeric((string) $data )) {
            return (float) $data;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( self::$FMT2, $SUBJECT, $argNoFmt, $data ));
    }

    /**
     * @param float $amount
     * @return string
     */
    public static function formatAmount( float $amount ) : string
    {
        static $DP = '.';
        static $TS = '';
        return number_format( $amount, 2, $DP, $TS );
    }

    /**
     * Assert data is an Boolean and return string
     *
     * @param mixed $data
     * @param int   $argIx
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function assertBoolean( $data, int $argIx = null ) : bool
    {
        static $TRUE    = 'true';
        static $FALSE   = 'false';
        static $SUBJECT = 'Boolean';
        switch( true ) {
            case is_bool( $data ) :
                return $data;
            case (( 1 == $data ) || ( $TRUE == strtolower( $data ))) :
                return true;
            case (( 0 == $data ) || ( $FALSE == strtolower( $data ))) :
                return false;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( self::$FMT2, $SUBJECT, $argNoFmt, $data ));
    }

    /**
     * Assert data is a Currency and return string
     *
     * @param mixed $data
     * @param int   $argIx
     * @return string
     */
    public static function assertCurrency( $data, int $argIx = null ) : string
    {
        static $SUBJECT = 'Currency';
        if( is_string( $data ) && ctype_upper( $data ) && ( 3 == strlen( $data ))) {
            return $data;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( self::$FMT2, $SUBJECT, $argNoFmt, $data ));
    }

    /**
     * Assert fileName is a readable file
     *
     * @param string $fileName
     * @throws InvalidArgumentException
     */
    public static function assertFileName( string $fileName )
    {
        static $FMT1 = '%s is no file';
        static $FMT2 = 'Can\'t read %s';
        self::assertString( $fileName );
        if( ! @is_file( $fileName )) {
            throw new InvalidArgumentException( sprintf( $FMT1, $fileName ));
        }
        if( ! @is_readable( $fileName )) {
            throw new InvalidArgumentException( sprintf( $FMT2, $fileName ));
        }
        clearstatcache( true, $fileName );
    }

    /**
     * Assert data is a gYearMonth and return string
     *
     * @param mixed $data
     * @param int   $argIx
     * @return string
     * @throws InvalidArgumentException
     */
    public static function assertGYearMonth( $data, int $argIx = null ) : string
    {
        static $SUBJECT = 'Year-month';
        if( is_scalar( $data ) &&
            checkdate((int) substr((string) $data, -2 ), 1, (int) substr((string) $data, 0, 4 ))) {
            return (string) $data;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( self::$FMT2, $SUBJECT, $argNoFmt, gettype( $data )));
    }

    /**
     * Assert data is in enumeration (string) array and return string
     *
     * @param mixed $data
     * @param array $enumeration
     * @param int   $argIx
     * @return string
     * @throws InvalidArgumentException
     */
    public static function assertInEnumeration( $data, array $enumeration, int $argIx = null ) : string
    {
        static $FMT2  = '%sexpected in enumeration %s, \'%s\' given.';
        static $COMMA = ',';
        if( is_string( $data ) && in_array( $data, $enumeration )) {
            return $data;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( $FMT2, $argNoFmt, implode( $COMMA, $enumeration ), $data ));
    }

    /**
     * Assert data is an Int and return string
     *
     * @param mixed $data
     * @param int   $argIx
     * @return string
     * @throws InvalidArgumentException
     */
    public static function assertInt( $data, int $argIx = null ) : string
    {
        static $SUBJECT = 'Int';
        if(  is_scalar( $data ) && ctype_digit((string) $data )) {
            return (string) $data;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( self::$FMT2, $SUBJECT, $argNoFmt, $data ));
    }


    /**
     * Assert data is an nonNegativeInteger and return int
     *
     * @param mixed $data
     * @param int   $argIx
     * @return int
     * @throws InvalidArgumentException
     */
    public static function assertNonNegativeInteger( $data, int $argIx = null ) : int
    {
        static $SUBJECT = 'nonNegativeInteger';
        if( is_scalar( $data ) && ctype_digit((string) $data ) && ( 0 <= (int) $data )) {
            return (int) $data;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( self::$FMT2, $SUBJECT, $argNoFmt, $data ));
    }

    /**
     * Assert data is an positiveInteger and return int
     *
     * @param mixed $data
     * @param int   $argIx
     * @return int
     * @throws InvalidArgumentException
     */
    public static function assertPositiveInteger( $data, int $argIx = null ) : int
    {
        static $SUBJECT = 'positiveInteger';
        if( is_scalar( $data ) && ctype_digit((string) $data ) && ( 0 < (int) $data )) {
            return (int) $data;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( self::$FMT2, $SUBJECT, $argNoFmt, $data ));
    }

    /**
     * Assert data is a string (i.e. is a scalar) and return string
     *
     * @param mixed $data
     * @param int   $argIx
     * @return string
     * @throws InvalidArgumentException
     */
    public static function assertString( $data, int $argIx = null ) : string
    {
        static $SUBJECT = 'String';
        if( is_scalar( $data )) {
            return (string) $data;
        }
        $argNoFmt = ( empty( $argIx )) ? null : sprintf( self::$FMT1, $argIx );
        throw new InvalidArgumentException( sprintf( self::$FMT2, $SUBJECT, $argNoFmt, gettype( $data )));
    }
}
