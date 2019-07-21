<?php

namespace Dllobell\SharpspringApi;

/**
 * This file is part of the dllobell/sharpspring-api package.
 *
 * (c) David Llobell <dllobellmoya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class Autoloader
 *
 * If you are not using Composer to manage class autoloading, here's an autoloader for this package.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class Autoloader
{
    /**
     * Registers Dllobell\SharpspringApi\Autoloader as an SPL autoloader.
     *
     * @param  bool $prepend Whether to prepend the autoloader or not.
     *
     * @return void
     */
    public static function register($prepend = false)
    {
        spl_autoload_register(array(__CLASS__, 'autoload'), true, $prepend);
    }

    /**
     * Handles autoloading of classes.
     *
     * @param  string $class A class name.
     *
     * @return void
     *
     * @throws Exception
     */
    public static function autoload($class)
    {
        if (strpos($class, 'Dllobell\\SharpspringApi\\') !== 0) {
            return;
        }

        if (is_file($file = __DIR__ . str_replace(array('Dllobell\\SharpspringApi\\', '\\'), '/', $class).'.php')) {
            require $file;
        }
    }
}
