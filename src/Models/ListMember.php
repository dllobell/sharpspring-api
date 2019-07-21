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
 * Class ListMember
 *
 * ListMember is a mapping table that corresponds leads in SharpSpring to the list they are members of.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class ListMember extends Model
{
    protected $attributes = [
        'id',
        'listID',
        'memberID',
        'isRemoved'
    ];
}
