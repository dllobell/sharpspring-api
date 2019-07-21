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
 * Class Request
 *
 * Class for Sharpspring requests
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Request
{

    /**
     * @var string The API method for this request.
     */
    protected $method;

    /**
     * @var array The parameters to send with this request.
     */
    protected $params = [];

    /**
     * Create a new Sharpspring request instance.
     *
     * @return void
     */
    public function __construct($method, $params)
    {
        $this->method = $method;
        $this->params = $params;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getEncodedData()
    {
        return json_encode([
            'method' => $this->getMethod(),
            'params' => $this->getParams(),
            'id' => uniqid()
        ]);
    }
}
