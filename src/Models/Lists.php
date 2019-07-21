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
 * Class Lists
 *
 * A List is a segmented audience of leads in SharpSpring.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Lists extends Model
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'name',
        'memberCount',
        'removedCount',
        'createTimestamp',
        'description'
    ];
}
