<?php

/**
 * This file is part of the dllobell/sharpspring-api package.
 *
 * (c) David Llobell <dllobellmoya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dllobell\SharpspringApi\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Dllobell\SharpspringApi\SharpspringClient;

/**
 * Class Sharpspring
 *
 * @package dllobell\sharpspring
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Sharpspring extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SharpspringClient::class;
    }
}
