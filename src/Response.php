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

use Dllobell\SharpspringApi\Exceptions\ResponseException;

/**
 * Class Response
 *
 * Class for Sharpspring responses
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Response
{
    protected $request;

    protected $body;

    protected $exception;

    /**
     * Create a new Sharpspring response instance.
     *
     * @return void
     */
    public function __construct(Request $request, $rawResponse)
    {
        $this->request = $request;

        $this->decodeResponse($rawResponse);
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getEndpoint()
    {
        return $this->getRequest()->getEndpoint();
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getResult()
    {
        return $this->getBody()->result;
    }

    public function getError()
    {
        return (array) $this->getBody()->error;
    }

    public function getId()
    {
        return $this->getBody()->id;
    }

    public function getCallCount()
    {
        return $this->getBody()->callCount;
    }

    public function getQueryLimit()
    {
        return $this->getBody()->queryLimit;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function isError()
    {
        return ! empty($this->getError());
    }

    public function makeException()
    {
        return $this->exception = new ResponseException($this);
    }

    protected function decodeResponse($rawResponse)
    {
        $this->body = json_decode($rawResponse);
    }
}
