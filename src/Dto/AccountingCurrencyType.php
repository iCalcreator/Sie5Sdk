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

class AccountingCurrencyType extends Sie5DtoBase implements Sie5DtoInterface
{
    /**
     * @var string|null
     *
     * Attribute name="currency" use="required" type="sie:Currency"
     * ISO 4217 code of the default accounting currency used by the company.
     * Any monetary amount in this file, except when currency is explicitly declared,
     * implicitly refers to this currency
     *
     * pattern value="[A-Z][A-Z][A-Z]"
     */
    private ?string $currency = null;

    /**
     * Factory method, set currency
     *
     * @param string $currency
     * @return static
     */
    public static function factoryCurrency( string $currency ) : self
    {
        return self::factory()->setCurrency( $currency );
    }

    /**
     * Return bool true is instance is valid
     *
     * @param null|array $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( null === $this->currency ) {
            $local[] = self::errMissing(self::class, self::CURRENCY );
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
    public function getCurrency() : ?string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return static
     */
    public function setCurrency( string $currency ) : self
    {
        $this->currency = $currency;
        return $this;
    }
}
