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
 * Class Lead
 *
 * The Lead table consists of prospects who are possibly interested in your product.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Lead extends ModelWithCustom
{
    const OPEN = 'open';
    const UNQUALIFIED = 'unqualified';
    const QUALIFIED = 'qualified';
    const CONTACT = 'contact';
    const CUSTOMER = 'customer';

    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'accountID',
        'ownerID',
        'campaignID',
        'leadStatus',
        'leadScore',
        'leadScoreWeighted',
        'persona',
        'active',
        'firstName',
        'lastName',
        'emailAddress',
        'companyName',
        'title',
        'street',
        'city',
        'country',
        'state',
        'zipcode',
        'website',
        'phoneNumber',
        'trackingID',
        'officePhoneNumber',
        'phoneNumberExtension',
        'mobilePhoneNumber',
        'faxNumber',
        'description',
        'industry',
        'isUnsubscribed',
        'updateTimestamp',
        'createTimestamp'
    ];
}
