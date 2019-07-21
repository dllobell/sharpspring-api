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
 * Class OpportunityLead
 *
 * OpportunityLead is a mapping table that represents an association between an Opportunity and Lead.
 * Each Opportunity in SharpSpring can consist of multiple Leads.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class OpportunityLead extends Model
{
    /**
     * @inheritDoc
     */
    protected $attributes = [
        'id',
        'opportunityID',
        'leadID'
    ];
}
