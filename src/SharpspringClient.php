<?php

/**
 * This file is part of the dllobell/sharpspring-api package.
 *
 * (c) David Llobell <dllobellmoya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dllobell\SharpspringApi;

use InvalidArgumentException;

use Dllobell\SharpspringApi\Models\Account;
use Dllobell\SharpspringApi\Models\Campaign;
use Dllobell\SharpspringApi\Models\Client;
use Dllobell\SharpspringApi\Models\DealStage;
use Dllobell\SharpspringApi\Models\Email;
use Dllobell\SharpspringApi\Models\EmailListing;
use Dllobell\SharpspringApi\Models\Field;
use Dllobell\SharpspringApi\Models\Lead;
use Dllobell\SharpspringApi\Models\Lists;
use Dllobell\SharpspringApi\Models\ListMember;
use Dllobell\SharpspringApi\Models\ListTag;
use Dllobell\SharpspringApi\Models\Opportunity;
use Dllobell\SharpspringApi\Models\OpportunityLead;
use Dllobell\SharpspringApi\Models\UserProfile;

use Dllobell\SharpspringApi\Results\CreateResult;
use Dllobell\SharpspringApi\Results\DeleteResult;
use Dllobell\SharpspringApi\Results\UpdateResult;

/**
 * Class SharpspringClient
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class SharpspringClient
{
    /**
     * Base url where the requests will be made.
     *
     * @var string
     */
    const URL_POINT = 'http://api.sharpspring.com/pubapi/';

    /**
     * Sharpspring API available versions.
     *
     * v1
     * Time zone arguments will be handled based on the time zone setting selected in the Company Profile.
     *
     * v1.2
     * Time zone arguments will be handled in UTC.
     *
     * @var array
     */
    const VERSIONS = [
        'v1',
        'v1.2'
    ];

    /**
     * Sharpspring account ID.
     *
     * @var string
     */
    protected $accountID;

    /**
     * Sharpspring secret key.
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Sharpspring api version.
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * @var Response|null Stores the response of the last request made.
     */
    protected $lastResponse;

    /**
     * Create a new Sharpspring client instance.
     *
     * @param  string  $accountID
     * @param  string  $secretKey
     * @param  string  $apiVersion
     *
     * @return void
     */
    public function __construct($accountID = null, $secretKey = null, $apiVersion = 'v1')
    {
        $this->setAccountID($accountID);
        $this->setSecretKey($secretKey);
        $this->setApiVersion($apiVersion);
    }

    /**
     * Set the client's account id.
     *
     * @param  string $accountID
     *
     * @return static
     */
    public function setAccountID($accountID)
    {
        $this->accountID = $accountID;

        return $this;
    }

    /**
     * Set the client's secret key.
     *
     * @param  string $secretKey
     *
     * @return static
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * Set the client's API version.
     *
     * @param  string $apiVersion
     *
     * @return static
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
        if (!in_array($apiVersion, self::VERSIONS)) {
            $this->apiVersion = self::VERSIONS[0];
        }

        return $this;
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Account
     */

    /**
     * Specify a list of Account objects to be created in SharpSpring.
     *
     * @param  Account[] $accounts
     *
     * @return (CreateResult)[]
     */
    public function createAccounts($accounts)
    {
        if (!is_array($accounts)) {
            $accounts = [$accounts];
        }

        $params = [
            'objects' => array_map(
                function ($account) {
                    return $account->getAllFilledAttributes();
                },
                $accounts
            )
        ];

        $result = $this->executeCall('createAccounts', $params);

        return $this->castResult($result->creates, CreateResult::class);
    }

    /**
     * Specify an Account to be created in SharpSpring.
     *
     * @param  Account $account
     *
     * @return (CreateResult)
     */
    public function createAccount($account)
    {
        $result = $this->createAccounts($account);

        return reset($result);
    }

    /**
     * Specify a list of Account IDs to be deleted in SharpSpring.
     *
     * @param  int[] $ids
     *
     * @return (DeleteResult)[]
     */
    public function deleteAccounts($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $params = [
            'objects' => array_map(
                function ($id) {
                    return ['id' => (int) $id];
                },
                $ids
            )
        ];

        $result = $this->executeCall('deleteAccounts', $params);

        return $this->castResult($result->deletes, DeleteResult::class);
    }

    /**
     * Retrieve a single Account by its ID.
     *
     * @param  int $id
     *
     * @return (Account|null)
     */
    public function getAccount($id)
    {
        $params = [
            'id' => (int) $id
        ];

        $result = $this->executeCall('getAccount', $params);

        if (empty($result->account)) {
            return null;
        }

        return $this->castResult(reset($result->account), Account::class);
    }

    /**
     * Retrieve a list of Accounts given a WHERE clause, or retrieve all Accounts if WHERE clause is empty.
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     *
     * @return (Account)[]
     */
    public function getAccounts($where = [], $limit = null, $offset = null)
    {
        $params = [
            'where' => $where,
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        $result = $this->executeCall('getAccounts', $params);

        return $this->castResult($result->account, Account::class);
    }

    /**
     * Retrieve a list of Accounts that have been either created or updated between two timestamps.
     * Timestamps must be specified in Y-m-d H:i:s format.
     *
     * @param  string $startDate
     * @param  string $endDate
     * @param  string $timestamp (none, create, update)
     *
     * @return (Account)[]
     */
    public function getAccountsDateRange($startDate, $endDate, $timestamp)
    {
        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if (!in_array($timestamp, ['create', 'update'])) {
            throw new InvalidArgumentException('Timestamp value must be either create or update');
        }

        $params['timestamp'] = $timestamp;

        $result = $this->executeCall('getAccountsDateRange', $params);

        return $this->castResult($result->account, Account::class);
    }

    /**
     * Specify a list of Account objects to be updated in SharpSpring.
     *
     * @param  Account[] $accounts
     *
     * @return (UpdateResult)[]
     */
    public function updateAccounts($accounts)
    {
        if (!is_array($accounts)) {
            $accounts = [$accounts];
        }

        $params = [
            'objects' => array_map(
                function ($account) {
                    return $account->getAllFilledAttributes();
                },
                $accounts
            )
        ];

        $result = $this->executeCall('updateAccounts', $params);

        return $this->castResult($result->updates, UpdateResult::class);
    }

    /**
     * Campaign
     */

    /**
     * Specify a list of Campaign objects to be created in SharpSpring.
     *
     * @param  Campaign[] $campaigns
     *
     * @return (CreateResult)[]
     */
    public function createCampaigns($campaigns)
    {
        if (!is_array($campaigns)) {
            $campaigns = [$campaigns];
        }

        $params = [
            'objects' => array_map(
                function ($campaign) {
                    return $campaign->getFilledAttributes();
                },
                $campaigns
            )
        ];

        $result = $this->executeCall('createCampaigns', $params);

        return $this->castResult($result->creates, CreateResult::class);
    }

    /**
     * Specify a list of Campaign IDs to be deleted in SharpSpring.
     *
     * @param  int[] $ids
     *
     * @return (DeleteResult)[]
     */
    public function deleteCampaigns($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $params = [
            'objects' => array_map(
                function ($id) {
                    return ['id' => (int) $id];
                },
                $ids
            )
        ];

        $result = $this->executeCall('deleteCampaigns', $params);

        return $this->castResult($result->deletes, DeleteResult::class);
    }

    /**
     * Retrieve a single Campaign by its ID.
     *
     * @param  int $id
     *
     * @return (Campaign|null)
     */
    public function getCampaign($id)
    {
        $params = [
            'id' => (int) $id
        ];

        $result = $this->executeCall('getCampaign', $params);

        if (empty($result->campaign)) {
            return null;
        }

        return $this->castResult(reset($result->campaign), Campaign::class);
    }

    /**
     * Retrieve a list of Campaigns given a WHERE clause, or retrieve all Campaigns if WHERE clause is empty.
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     *
     * @return (Campaign)[]
     */
    public function getCampaigns($where = [], $limit = null, $offset = null)
    {
        $params = [
            'where' => $where,
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        $result = $this->executeCall('getCampaigns', $params);

        return $this->castResult($result->campaign, Campaign::class);
    }

    /**
     * Retrieve a list of Campaigns that have been either created or updated between two timestamps.
     * Timestamps must be specified in Y-m-d H:i:s format.
     *
     * @param  string $startDate
     * @param  string $endDate
     * @param  string $timestamp (none, create, update)
     *
     * @return (Campaign)[]
     */
    public function getCampaignsDateRange($startDate, $endDate, $timestamp)
    {
        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if (!in_array($timestamp, ['create', 'update'])) {
            throw new InvalidArgumentException('Timestamp value must be either create or update');
        }

        $params['timestamp'] = $timestamp;

        $result = $this->executeCall('getCampaignsDateRange', $params);

        return $this->castResult($result->campaign, Campaign::class);
    }

    /**
     * Specify a list of Campaign objects to be updated in SharpSpring.
     *
     * @param  Campaign[] $campaigns
     *
     * @return (UpdateResult)[]
     */
    public function updateCampaigns($campaigns)
    {
        if (!is_array($campaigns)) {
            $campaigns = [$campaigns];
        }

        $params = [
            'objects' => array_map(
                function ($campaign) {
                    return $campaign->getFilledAttributes();
                },
                $campaigns
            )
        ];

        $result = $this->executeCall('updateCampaigns', $params);

        return $this->castResult($result->updates, UpdateResult::class);
    }

    /**
     * Client
     */

    /**
     * Get a list of all active companies managed by your company.
     *
     * @return (Client)[]
     */
    public function getClients()
    {
        $result = $this->executeCall('getClients');

        return $this->castResult($result->getAllcompanyProfileManagedBys, Client::class);
    }

    /**
     * DealStage
     */

    /**
     * Specify a list of DealStage objects to be created in SharpSpring.
     *
     * @param  DealStage[] $dealStages
     *
     * @return (CreateResult)[]
     */
    public function createDealStages($dealStages)
    {
        if (!is_array($dealStages)) {
            $dealStages = [$dealStages];
        }

        $params = [
            'objects' => array_map(
                function ($dealStage) {
                    return $dealStage->getFilledAttributes();
                },
                $dealStages
            )
        ];

        $result = $this->executeCall('createDealStages', $params);

        return $this->castResult($result->creates, CreateResult::class);
    }

    /**
     * Specify a list of DealStage IDs to be deleted in SharpSpring.
     *
     * @param  int[] $ids
     *
     * @return (DeleteResult)[]
     */
    public function deleteDealStages($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $params = [
            'objects' => array_map(
                function ($id) {
                    return ['id' => (int) $id];
                },
                $ids
            )
        ];

        $result = $this->executeCall('deleteDealStages', $params);

        return $this->castResult($result->deletes, DeleteResult::class);
    }

    /**
     * Retrieve a single DealStage by its ID.
     *
     * @param  int $id
     *
     * @return (DealStage|null)
     */
    public function getDealStage($id)
    {
        $params = [
            'id' => (int) $id
        ];

        $result = $this->executeCall('getDealStage', $params);

        if (empty($result->dealStage)) {
            return null;
        }

        return $this->castResult(reset($result->dealStage), DealStage::class);
    }

    /**
     * Retrieve a list of DealStage objects given a WHERE clause, or retrieve all DealStage objects if WHERE clause is empty
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     *
     * @return (DealStage)[]
     */
    public function getDealStages($where = [], $limit = null, $offset = null)
    {
        $params = [
            'where' => $where,
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        $result = $this->executeCall('getDealStages', $params);

        return $this->castResult($result->dealStage, DealStage::class);
    }

    /**
     * Retrieve a list of DealStages that have been either created or updated between two timestamps.
     * Timestamps must be specified in Y-m-d H:i:s format.
     *
     * @param  string $startDate
     * @param  string $endDate
     * @param  string $timestamp (none, create, update)
     *
     * @return (DealStage)[]
     */
    public function getDealStagesDateRange($startDate, $endDate, $timestamp)
    {
        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if (!in_array($timestamp, ['create', 'update'])) {
            throw new InvalidArgumentException('Timestamp value must be either create or update');
        }

        $params['timestamp'] = $timestamp;

        $result = $this->executeCall('getDealStagesDateRange', $params);

        return $this->castResult($result->dealStage, DealStage::class);
    }

    /**
     * Email
     */

    /**
     * Create an email from html.
     *
     * @param  Email[] $emails
     *
     * @return (CreateResult)[]
     */
    public function createEmail($emails)
    {
        if (!is_array($emails)) {
            $emails = [$emails];
        }

        $params = [
            'objects' => array_map(
                function ($email) {
                    return $email->getFilledAttributes();
                },
                $emails
            )
        ];

        $result = $this->executeCall('createEmail', $params);

        return $this->castResult($result->creates, CreateResult::class);
    }

    /**
     * Specify a email ID to be deleted in SharpSpring.
     *
     * @param  int[] $ids
     *
     * @return (DeleteResult)[]
     */
    public function deleteEmail($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $params = [
            'objects' => array_map(
                function ($id) {
                    return ['id' => (int) $id];
                },
                $ids
            )
        ];

        $result = $this->executeCall('deleteEmail', $params);

        return $this->castResult($result->deletes, DeleteResult::class);
    }

    public function getEmail($id)
    {
        $params = [
            'id' => (int) $id
        ];

        $result = $this->executeCall('getEmail', $params);

        if (empty($result->email)) {
            return null;
        }

        return $this->castResult(reset($result->email), Email::class);
    }

    /**
     * Returns a list of email information.
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     *
     * @return (EmailListing)[]
     */
    public function getEmailListing($where = [], $limit = null, $offset = null)
    {
        $params = [
            'where' => $where,
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        $result = $this->executeCall('getEmailListing', $params);

        return $this->castResult($result->getAllemailListings, EmailListing::class);
    }

    /**
     * Specify a list of existing Emails to be updated in SharpSpring.
     * The 'id' of each email must be passed for each record being updated.
     *
     * @param  Email[] $emails
     *
     * @return (UpdateResult)[]
     */
    public function updateEmail($emails)
    {
        if (!is_array($emails)) {
            $emails = [$emails];
        }

        $params = [
            'objects' => array_map(
                function ($email) {
                    return $email->getFilledAttributes();
                },
                $emails
            )
        ];

        $result = $this->executeCall('updateEmail', $params);

        return $this->castResult($result->updates, UpdateResult::class);
    }

    /**
     * Field
     */

    /**
     * Specify a list of field objects to be created in SharpSpring.
     * Each field must, at minimum, have a relationship (ex. opportunity, lead, or account), dataType (text, picklist, etc.), and label.
     *
     * @param  Field[] $fields
     *
     * @return (UpdateResult)[]
     */
    public function createFields($fields)
    {
        if (!is_array($fields)) {
            $fields = [$fields];
        }

        $params = [
            'objects' => array_map(
                function ($field) {
                    return $field->getFilledAttributes();
                },
                $fields
            )
        ];

        $result = $this->executeCall('createFields', $params);

        return $this->castResult($result->creates, CreateResult::class);
    }

    /**
     * Specify a list of fields to be deleted in SharpSpring.
     * Only custom fields can be deleted. These fields are deleted by systemName.
     *
     * @param  string[] $systemNames
     *
     * @return (DeleteResult)[]
     */
    public function deleteFields($systemNames)
    {
        if (!is_array($systemNames)) {
            $systemNames = [$systemNames];
        }

        $params = [
            'objects' => array_map(
                function ($systemName) {
                    return ['systemName' => (string) $systemName];
                },
                $systemNames
            )
        ];

        $result = $this->executeCall('deleteFields', $params);

        return $this->castResult($result->deletes, DeleteResult::class);
    }

    /**
     * Retrieve a list of Field objects.
     * This function is extremely useful for retrieving a list of all custom fields and system fields available in SharpSpring.
     * Every result will have a "systemName" which is the key that must be specified in order to update that field for a specific lead.
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     *
     * @return (Field)[]
     */
    public function getFields($where = [], $limit = null, $offset = null)
    {
        $params = [
            'where' => $where,
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        $result = $this->executeCall('getFields', $params);

        return $this->castResult($result->field, Field::class);
    }

    /**
     * Specify a list of existing Fields to be updated in SharpSpring.
     * The 'id' of each field must be passed for each record being updated.
     *
     * @param  Field[] $fields
     *
     * @return (UpdateResult)[]
     */
    public function updateFields($fields)
    {
        if (!is_array($fields)) {
            $fields = [$fields];
        }

        $params = [
            'objects' => array_map(
                function ($field) {
                    return $field->getFilledAttributes();
                },
                $fields
            )
        ];

        $result = $this->executeCall('updateFields', $params);

        return $this->castResult($result->updates, UpdateResult::class);
    }

    /**
     * ListTag
     */

    /**
     * Returns a tag list for lists.
     *
     * @return (ListTag)[]
     */
    public function getListTags()
    {
        $result = $this->executeCall('getListTags');

        return $this->castResult($result->getAllgetListTagss, ListTag::class);
    }

    /**
     * Lead
     */

    /**
     * Specify a list of Lead objects to be created in SharpSpring.
     * Every lead object is a hash keyed by the system name of the lead field.
     * If you wish to push custom fields, first use the "getFields" API method in order to retrieve a list of custom fields.
     * In order to set a custom field for a lead, use the field's "systemName" attribute as the key.
     *
     * Note:
     * This method accepts up to 500 lead objects per call.
     * However, in instances with a lot of custom field data being passed, it's advisable to chunk the requests into smaller, more manageable calls to improve performance.
     * 250 lead objects is recommended in those cases.
     *
     * @param  Lead[] $leads
     *
     * @return (CreateResult)[]
     */
    public function createLeads($leads)
    {
        if (!is_array($leads)) {
            $leads = [$leads];
        }

        $params = [
            'objects' => array_map(
                function ($lead) {
                    return $lead->getAllFilledAttributes();
                },
                $leads
            )
        ];

        $result = $this->executeCall('createLeads', $params);

        return $this->castResult($result->creates, CreateResult::class);
    }

    /**
     * Specify a list of leads to be deleted in SharpSpring by ID.
     *
     * @param  int[] $ids
     *
     * @return (DeleteResult)[]
     */
    public function deleteLeads($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $params = [
            'objects' => array_map(
                function ($id) {
                    return ['id' => (int) $id];
                },
                $ids
            )
        ];

        $result = $this->executeCall('deleteLeads', $params);

        return $this->castResult($result->deletes, DeleteResult::class);
    }

    /**
     * Retrieve a single Lead by its ID.
     *
     * @param  int $id
     *
     * @return (Lead|null)
     */
    public function getLead($id)
    {
        $params = [
            'id' => (int) $id
        ];

        $result = $this->executeCall('getLead', $params);

        if (empty($result->lead)) {
            return null;
        }

        return $this->castResult(reset($result->lead), Lead::class);
    }

    /**
     * Retrieve a list of Leads given a WHERE clause, or retrieve all Leads if WHERE clause is empty.
     * If a list is used in the parameters then non-list parameters will be ignored.
     * A maximum of 500 leads will be returned with list<id> being selected first.
     * If FIELDS is specified, ONLY the fields requested will be returned
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     * @param  array $fields
     *
     * @return (Lead)[]
     */
    public function getLeads($where = [], $limit = null, $offset = null, $fields = [])
    {
        $params = [
            'where' => $where
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        if (!empty($fields)) {
            $params['fields'] = $fields;
        }

        $result = $this->executeCall('getLeads', $params);

        return $this->castResult($result->lead, Lead::class);
    }

    /**
     * Retrieve a list of Leads that have been either created or updated between two timestamps.
     * Timestamps must be specified in Y-m-d H:i:s format.
     * If FIELDS is specified, only the fields requested will be returned.
     *
     * @param  string $startDate
     * @param  string $endDate
     * @param  string $timestamp (none, create, update)
     *
     * @return (Lead)[]
     */
    public function getLeadsDateRange($startDate, $endDate, $timestamp, $fields = [])
    {
        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        if (!in_array($timestamp, ['create', 'update'])) {
            throw new InvalidArgumentException('Timestamp value must be either create or update');
        }

        $params['timestamp'] = $timestamp;

        if (!empty($fields)) {
            $params['fields'] = $fields;
        }

        $result = $this->executeCall('getLeadsDateRange', $params);

        return $this->castResult($result->lead, Lead::class);
    }

    /**
     * Subscribe a URL to receive real-time lead updates.
     * We will POST a list of updated leads whenever leads are updated in SharpSpring.
     *
     * @param  string $url
     *
     * @return bool
     */
    public function subscribeToLeadUpdates($url)
    {
        $params = [
            'url' => $url
        ];

        $result = $this->executeCall('subscribeToLeadUpdates', $params);

        return $result->success;
    }

    /**
     * Specify a list of Lead objects to be updated in SharpSpring.
     * Every lead object is a hash keyed by the system name of the lead field.
     * If you wish to push custom fields, first use the "getFields" API method in order to retrieve a list of custom fields.
     * In order to set a custom field for a lead, use the field's "systemName" attribute as the key.
     *
     * @param  Lead[] $leads
     *
     * @return (UpdateResult)[]
     */
    public function updateLeads($leads)
    {
        if (!is_array($leads)) {
            $leads = [$leads];
        }

        $params = [
            'objects' => array_map(
                function ($lead) {
                    return $lead->getAllFilledAttributes();
                },
                $leads
            )
        ];

        $result = $this->executeCall('updateLeads', $params);

        return $this->castResult($result->updates, UpdateResult::class);
    }

    /**
     * This differs from updateLeads in that it returns error code 404 if the lead id does not exist or the lead id is not provided and the lead email does not exist.
     *
     * @param  Lead[] $leads
     *
     * @return (UpdateResult)[]
     */
    public function updateLeadsV2($leads)
    {
        if (!is_array($leads)) {
            $leads = [$leads];
        }

        $params = [
            'objects' => array_map(
                function ($lead) {
                    return $lead->getAllFilledAttributes();
                },
                $leads
            )
        ];

        $result = $this->executeCall('updateLeadsV2', $params);

        return $this->castResult($result->updates, UpdateResult::class);
    }

    /**
     * Retrieve a single Lead by its emailAddress.
     *
     * @param  string $emailAddress
     *
     * @return (Lead|null)
     */
    public function getLeadByEmail($emailAddress)
    {
        $where = [
            'emailAddress' => $emailAddress
        ];

        $leads = $this->getLeads($where);

        if (empty($leads)) {
            return null;
        }

        return reset($leads);
    }

    /**
     * List
     */

    public function createList()
    {
    }

    /**
     * Specify a list of Lists to be deleted in SharpSpring by ID.
     *
     * @param  int[] $ids
     *
     * @return (DeleteResult)[]
     */
    public function deleteList($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $params = [
            'objects' => array_map(
                function ($id) {
                    return ['id' => (int) $id];
                },
                $ids
            )
        ];

        $result = $this->executeCall('deleteList', $params);

        return $this->castResult($result->deletes, DeleteResult::class);
    }

    /**
     * Returns a list of active Lists.
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     *
     * @return (Lists)[]
     */
    public function getActiveLists($where = [], $limit = null, $offset = null)
    {
        $params = [
            'where' => $where,
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        $result = $this->executeCall('getActiveLists', $params);

        return $this->castResult($result->activeList, Lists::class);
    }

    /**
     * Retrieve a single List by its ID.
     *
     * @param  int $id
     *
     * @return (Lists|null)
     */
    public function getActiveList($id)
    {
        $where = [
            'id' => (int) $id
        ];

        $activeList = $this->getActiveLists($where);

        if (empty($activeList)) {
            return null;
        }

        return reset($activeList);
    }

    /**
     * ListMember
     */

    /**
     * Adds a lead to a list.
     *
     * @param  int $listID
     * @param  int $memberID
     *
     * @return (CreateResult)
     */
    public function addListMember($listID, $memberID)
    {
        $params = [
            'listID' => (int) $listID,
            'memberID' => (int) $memberID
        ];

        $result = $this->executeCall('addListMember', $params);

        return $this->castResult(reset($result->creates), CreateResult::class);
    }

    /**
     * Add a member to a mailing list using their email address.
     *
     * @param  int $listID
     * @param  string $emailAddress
     *
     * @return (CreateResult)
     */
    public function addListMemberEmailAddress($listID, $emailAddress)
    {
        $params = [
            'listID' => (int) $listID,
            'emailAddress' => $emailAddress
        ];

        $result = $this->executeCall('addListMemberEmailAddress', $params);

        return $this->castResult(reset($result->creates), CreateResult::class);
    }

    /**
     * Specify a list of leads by id and lists for managing memberships in bulk.
     * Each call can handle up to 500 associations.
     *
     * @param  int $listID
     * @param  int $memberID
     *
     * @return (CreateResult)
     */
    public function addListMembers($listID, $memberID)
    {
        $params = [
            'listID' => (int) $listID,
            'memberID' => (int) $memberID
        ];

        $result = $this->executeCall('addListMembers', $params);

        return $this->castResult(reset($result->creates), CreateResult::class);
    }

    /**
     * Specify a list of leads by email address and lists for managing memberships in bulk.
     * Each call can handle up to 500 associations.
     *
     * @param  int $listID
     * @param  string $emailAddress
     *
     * @return (CreateResult)
     */
    public function addListMembersEmailAddress($listID, $emailAddress)
    {
        $params = [
            'listID' => (int) $listID,
            'emailAddress' => $emailAddress
        ];

        $result = $this->executeCall('addListMembersEmailAddress', $params);

        return $this->castResult(reset($result->creates), CreateResult::class);
    }

    /**
     * Get the lists that a particular contact is a member of.
     *
     * @param array $where
     *
     * @return (ListMember)[]
     */
    public function getContactListMemberships($where = [])
    {
        $params = [
            'where' => $where
        ];

        $result = $this->executeCall('getContactListMemberships', $params);

        return $this->castResult($result->listMember, ListMember::class);
    }

    /**
     * Opportunity
     */

    /**
     * Specify a list of Opportunity objects to be created in SharpSpring.
     *
     * @param  Opportunity[] $opportunities
     *
     * @return (CreateResult)[]
     */
    public function createOpportunities($opportunities)
    {
        if (!is_array($opportunities)) {
            $opportunities = [$opportunities];
        }

        $params = [
            'objects' => array_map(
                function ($opportunity) {
                    return $opportunity->getAllFilledAttributes();
                },
                $opportunities
            )
        ];

        $result = $this->executeCall('createOpportunities', $params);

        return $this->castResult($result->creates, CreateResult::class);
    }

    /**
     * Specify an Opportunity to be created in SharpSpring.
     *
     * @param  Opportunity $opportunity
     *
     * @return (CreateResult)
     */
    public function createOpportunity($opportunity)
    {
        $result = $this->createOpportunities($opportunity);

        return reset($result);
    }

    /**
     * Specify a list of Opportunity IDs to be deleted in SharpSpring.
     *
     * @param  int[] $ids
     *
     * @return (DeleteResult)[]
     */
    public function deleteOpportunities($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $params = [
            'objects' => array_map(
                function ($id) {
                    return ['id' => (int) $id];
                },
                $ids
            )
        ];

        $result = $this->executeCall('deleteOpportunities', $params);

        return $this->castResult($result->deletes, DeleteResult::class);
    }

    /**
     * Retrieve a list of Opportunities given a WHERE clause, or retrieve all Opportunities if WHERE clause is empty.
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     *
     * @return (Opportunity)[]
     */
    public function getOpportunities($where = [], $limit = null, $offset = null)
    {
        $params = [
            'where' => $where,
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        $result = $this->executeCall('getOpportunities', $params);

        return $this->castResult($result->opportunity, Opportunity::class);
    }

    /**
     * Retrieve a list of Opportunities that have been either created or updated between two timestamps.
     * Timestamps must be specified in Y-m-d H:i:s format.
     *
     * @param  string $startDate
     * @param  string $endDate
     * @param  string $timestamp (none, create, update)
     *
     * @return (Opportunity)[]
     */
    public function getOpportunitiesDateRange($startDate, $endDate, $timestamp)
    {
        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if (!in_array($timestamp, ['create', 'update'])) {
            throw new InvalidArgumentException('Timestamp value must be either create or update');
        }

        $params['timestamp'] = $timestamp;

        $result = $this->executeCall('getOpportunitiesDateRange', $params);

        return $this->castResult($result->opportunity, Opportunity::class);
    }

    /**
     * Retrieve a single Opportunity by its ID.
     *
     * @param  int $id
     *
     * @return (Opportunity|null)
     */
    public function getOpportunity($id)
    {
        $params = [
            'id' => $id
        ];

        $result = $this->executeCall('getOpportunity', $params);

        if (empty($result->opportunity)) {
            return null;
        }

        return $this->castResult(reset($result->opportunity), Opportunity::class);
    }

    /**
     * Specify a list of Opportunity objects to be updated in SharpSpring.
     *
     * @param  Opportunity[] $opportunities
     *
     * @return (UpdateResult)[]
     */
    public function updateOpportunities($opportunities)
    {
        if (!is_array($opportunities)) {
            $opportunities = [$opportunities];
        }

        $params = [
            'objects' => array_map(
                function ($opportunity) {
                    return $opportunity->getAllFilledAttributes();
                },
                $opportunities
            )
        ];

        $result = $this->executeCall('updateOpportunities', $params);

        return $this->castResult($result->updates, UpdateResult::class);
    }

    /**
     * Specify an Opportunity to be updated in SharpSpring.
     *
     * @param  Opportunity $opportunity
     *
     * @return (UpdateResult)
     */
    public function updateOpportunity($opportunity)
    {
        $result = $this->updateOpportunities($opportunity);

        return reset($result);
    }

    /**
     * OpportunityLead
     */


    /**
     * Specify a list of OpportunityLead objects to be created in SharpSpring.
     *
     * @param  OpportunityLead[] $opportunityLeads
     *
     * @return (CreateResult)[]
     */
    public function createOpportunityLeads($opportunityLeads)
    {
        if (!is_array($opportunityLeads)) {
            $opportunityLeads = [$opportunityLeads];
        }

        $params = [
            'objects' => array_map(
                function ($opportunityLead) {
                    return $opportunityLead->getFilledAttributes();
                },
                $opportunityLeads
            )
        ];

        $result = $this->executeCall('createOpportunityLeads', $params);

        return $this->castResult($result->creates, CreateResult::class);
    }

    /**
     * Specify a list of OpportunityLead IDs to be deleted in SharpSpring.
     *
     * @param  int[] $ids
     *
     * @return (DeleteResult)[]
     */
    public function deleteOpportunityLeads($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $params = [
            'objects' => array_map(
                function ($id) {
                    return ['id' => (int) $id];
                },
                $ids
            )
        ];

        $result = $this->executeCall('deleteOpportunityLeads', $params);

        return $this->castResult($result->deletes, DeleteResult::class);
    }

    /**
     * Retrieve a single OpportunityLead by its ID.
     *
     * @param  int $id
     *
     * @return (OpportunityLead|null)
     */
    public function getOpportunityLead($id)
    {
        $params = [
            'id' => (int) $id
        ];

        $result = $this->executeCall('getOpportunityLead', $params);

        if (empty($result->opportunityLead)) {
            return null;
        }

        return $this->castResult(reset($result->opportunityLead), OpportunityLead::class);
    }

    /**
     * Retrieve a list of Opportunities given a WHERE clause, or retrieve all Opportunities if WHERE clause is empty.
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     *
     * @return (OpportunityLead)[]
     */
    public function getOpportunityLeads($where = [], $limit = null, $offset = null)
    {
        $params = [
            'where' => $where,
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        $result = $this->executeCall('getOpportunityLeads', $params);

        return $this->castResult($result->getWhereopportunityLeads, OpportunityLead::class);
    }

    /**
     * Retrieve a list of Opportunities that have been either created or updated between two timestamps.
     * Timestamps must be specified in Y-m-d H:i:s format.
     *
     * @param  string $startDate
     * @param  string $endDate
     * @param  string $timestamp (none, create, update)
     *
     * @return (OpportunityLead)[]
     */
    public function getOpportunityLeadsDateRange($startDate, $endDate, $timestamp)
    {
        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if (!in_array($timestamp, ['create', 'update'])) {
            throw new InvalidArgumentException('Timestamp value must be either create or update');
        }

        $params['timestamp'] = $timestamp;

        $result = $this->executeCall('getOpportunityLeadsDateRange', $params);

        return $this->castResult($result->opportunityLead, OpportunityLead::class);
    }

    /**
     * UserProfile
     */

    /**
     * Retrieve a list of UserProfile objects.
     *
     * @param  array $where
     * @param  int $limit
     * @param  int $offset
     *
     * @return (UserProfile)[]
     */
    public function getUserProfiles($where = [], $limit = null, $offset = null)
    {
        $params = [
            'where' => $where,
        ];

        if ($limit) {
            $params['limit'] = (int) $limit;
        }

        if ($offset) {
            $params['offset'] = (int) $offset;
        }

        $result = $this->executeCall('getUserProfiles', $params);

        return $this->castResult($result->userProfile, UserProfile::class);
    }

    /**
     * Execute the cURL call
     *
     * @param string $method
     * @param array $params
     *
     * @return mixed
     *
     * @throws \Dllobell\SharpspringApi\Exceptions\SharpspringException
     */
    private function executeCall($method, $params = [])
    {
        $request = new Request($method, $params);

        $data = $request->getEncodedData();

        $ch = curl_init($this->getUrl());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);

        $rawResponse = curl_exec($ch);
        curl_close($ch);

        $response = new Response($request, $rawResponse);

        if ($response->isError()) {
            throw $response->makeException();
        }

        $this->lastResponse = $response;

        return $response->getResult();
    }

    /**
     * Get the URL endpoint
     *
     * @return string
     */
    private function getUrl()
    {
        $queryString = http_build_query([
            'accountID' => $this->accountID,
            'secretKey' => $this->secretKey
        ]);

        return self::URL_POINT . $this->apiVersion . '/?' . $queryString;
    }

    /**
     * Cast the result to the specified class
     *
     * @param  array $result
     * @param  string $class
     *
     * @return array
     */
    private function castResult($result, $class)
    {
        if (!is_array($result)) {
            return $this->cast($result, $class);
        }

        return $this->castCollection($result, $class);
    }

    /**
     * Collection casting
     *
     * @param  array $collection
     * @param  string $class
     *
     * @return array
     */
    private function castCollection($collection, $class)
    {
        return array_map(
            function ($object) use ($class) {
                return $this->cast($object, $class);
            },
            $collection
        );
    }

    /**
     * Class casting
     *
     * @param  object $sourceObject
     * @param  string $class
     *
     * @return object
     */
    private function cast($object, $class)
    {
        return new $class((array) $object);
    }
}
