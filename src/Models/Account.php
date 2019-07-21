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

use Dllobell\SharpspringApi\ModelWithCustom;

/**
 * Class Account
 *
 * An Account is an organization or person that is associated with an Opportunity.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Account extends ModelWithCustom
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'ownerID',
        'accountName',
        'industry',
        'phone',
        'annualRevenue',
        'numberOfEmployees',
        'website',
        'yearStarted',
        'fax',
        'billingCity',
        'billingCountry',
        'billingPostalCode',
        'billingState',
        'billingStreetAddress',
        'shippingCity',
        'shippingCountry',
        'shippingPostalCode',
        'shippingState',
        'shippingStreetAddress'
    ];
}
