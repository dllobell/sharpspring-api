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
 * Class Opportunity
 *
 * An Opportunity represents a potential deal that has an expected value.
 * An Opportunity can be associated with a Lead or Account, as well as a Campaign.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Opportunity extends ModelWithCustom
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'ownerID',
        'dealStageID',
        'accountID',
        'campaignID',
        'opportunityName',
        'probability',
        'amount',
        'isClosed',
        'isWon',
        'isActive',
        'closeDate',
        'originatingLeadID',
        'primaryLeadID'
    ];

    /**
     * @inheritDoc
     */
    protected $nullable = [
        'opportunityName',
        'closeDate'
    ];
}
