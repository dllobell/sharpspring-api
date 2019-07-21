<?php

/**
 * This file is part of the dllobell/sharpspring-api package.
 *
 * (c) David Llobell <dllobellmoya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dllobell\SharpspringApi\Models;

use Dllobell\SharpspringApi\Model;

/**
 * Class Client
 *
 * The Client table contains information on Agency instances managed by the Partner instance.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Client extends Model
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'companyName',
        'streetAddress',
        'zipCode',
        'country',
        'state',
        'city'
    ];
}
