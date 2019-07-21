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
 * Class EmailListing
 *
 * An Email Listing contains information about a group of emails, including the id, HTML content, subject line, and thumbnail image.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class EmailListing extends Model
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'createTimestamp',
        'title',
        'subject',
        'thumbnail'
    ];
}
