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
 * Class Field
 *
 * The field table contains metadata for a sharpspring field. This includes both lead and opportunity fields.
 * The 'system name' is a key that can be specified in any POST to create or update an object.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Field extends Model
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'relationship',
        'systemName',
        'label',
        'source',
        'dataType',
        'dataLength',
        'isRequired',
        'isCustom',
        'isActive',
        'isAvailableInContactManager',
        'isEditableInContactManager',
        'isAvailableInForms'
    ];
}
