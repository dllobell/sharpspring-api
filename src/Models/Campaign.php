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
 * Class Campaign
 *
 * A Campaign is a marketing project that we track in SharpSpring.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Campaign extends Model
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'campaignName',
        'campaignType',
        'campaignAlias',
        'campaignOrigin',
        'qty',
        'price',
        'goal',
        'otherCosts',
        'startDate',
        'endDate',
        'isActive'
    ];
}
