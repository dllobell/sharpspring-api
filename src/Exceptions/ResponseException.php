<?php

/**
 * This file is part of the dllobell/sharpspring-api package.
 *
 * (c) David Llobell <dllobellmoya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dllobell\SharpspringApi\Exceptions;

use Dllobell\SharpspringApi\Response;
use Dllobell\SharpspringApi\Exceptions\SharpspringException;

/**
 * Class SharpspringException
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class ResponseException extends SharpspringException
{
    protected $response;

    protected $data;

    public function __construct(Response $response, SharpspringException $previous = null)
    {
        $this->response = $response;

        $error = $response->getError()[0];
        $this->data = $error->data;

        parent::__construct($error->message, $error->code, $previous);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getData()
    {
        return $this->data;
    }

    public function __get($key)
    {
        if (isset($this->data->{$key})) {
            return $this->data->{$key};
        }

        return null;
    }
}
