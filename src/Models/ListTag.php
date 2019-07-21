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
 * Class ListTag
 *
 * ListTag is a table of all list tags in an instance of SharpSpring.
 * List tags are used to group similar lists together for aggregate list sends, automation, and reporting.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class ListTag extends Model
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'label',
        'objectType'
    ];
}
