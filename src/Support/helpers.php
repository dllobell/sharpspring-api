<?php

/**
 * This file is part of the dllobell/sharpspring-api package.
 *
 * (c) David Llobell <dllobellmoya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('sharpspring_submit_form')) {
    function sharpspring_submit_form($baseURL, $endpoint, $params)
    {
        $request = $baseURL . $endpoint . '/jsonp/?';

        foreach ($params as $key => $value) {
            $request .= $key . '=' . urlencode($value) . '&';
        }

        $request = rtrim($request, '&');

        if (isset($_COOKIE['__ss_tk'])) {
            $request .= 'trackingid__sb=' . urlencode($_COOKIE['__ss_tk']);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}
