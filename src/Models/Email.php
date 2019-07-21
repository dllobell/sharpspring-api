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
 * Class Email
 *
 * An html email created in SharpSpring.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Email extends Model
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'html',
        'emailName',
        'subject',
        'fromName',
        'fromEmail',
        'replyTo',
        'unsubCategory',
        'contactManager',
        'repeatable',
        'fromLeadOwner'
    ];
}
