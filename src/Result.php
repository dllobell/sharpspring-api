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

/**
 * Class Result
 *
 * Base class for Sharpspring result models (CreateResult, UpdateResult, DeleteResult)
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
abstract class Result
{
    /**
     * Determine if the operation has succeded
     *
     * @var bool
     */
    protected $success;

    /**
     * The error object if the operation fails
     *
     * @var object
     */
    protected $error;

    /**
     * Create a new Result instance.
     *
     * @return void
     */
    public function __construct(array $attributes)
    {
        if (array_key_exists('success', $attributes)) {
            $this->success = $attributes['success'];
        }

        if (array_key_exists('error', $attributes)) {
            $this->error = $attributes['error'];
        }
    }

    public function success()
    {
        return (bool) $this->success;
    }

    public function getError()
    {
        return $this->error;
    }
}
