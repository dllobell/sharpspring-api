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
 * Class UserProfile
 *
 * The User Profile table consists of SharpSpring user accounts.
 * This ID can be used in the lead owner field in the Lead table as well as in the Opportunity table.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class UserProfile extends Model
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'firstName',
        'lastName',
        'displayName',
        'emailAddress',
        'isActive',
        'isReseller',
        'userTimezone',
        'phone'
    ];
}
