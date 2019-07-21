<?php

/**
 * This file is part of the dllobell/sharpspring-api package.
 *
 * (c) David Llobell <dllobellmoya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dllobell\SharpspringApi\Results;

use Dllobell\SharpspringApi\Result;

/**
 * Class CreateResult
 *
 * CreateResult contains information about the success of a create operation, an array of any errors associated with the operation, and the ID of the newly created record.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class CreateResult extends Result
{
    protected $id;

    /**
     * Create a new CreateResult instance.
     *
     * @return void
     */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        if (array_key_exists('id', $attributes)) {
            $this->id = $attributes['id'];
        }
    }

    public function getId()
    {
        return $this->id;
    }
}
