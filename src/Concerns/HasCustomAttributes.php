<?php

/**
 * This file is part of the dllobell/sharpspring-api package.
 *
 * (c) David Llobell <dllobellmoya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dllobell\SharpspringApi\Concerns;

/**
 * Trait HasCustomAttributes
 *
 * Adds custom attributes functionality to Sharpspring models that can have custom fields.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
trait HasCustomAttributes
{
    /**
     * The model's custom attributes.
     *
     * @var array
     */
    protected $customAttributes = [];

    /**
     * The model's mapped custom attributes.
     *
     * @var array
     */
    protected static $customAttributesMap = [];

    /**
     * Get all custom attributes from the model.
     *
     * @return array
     */
    public function getCustomAttributes()
    {
        return $this->customAttributes;
    }

    /**
     * Remove the given custom attribute of the model
     *
     * @param  string  $key
     */
    public function clearCustomAttribute($key)
    {
        unset($this->customAttributes[$key]);
    }

    /**
     * Remove the given custom attributes of the model
     *
     * @param  array  $keys
     */
    public function clearCustomAttributes($keys = [])
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        if (empty($keys)) {
            $this->customAttributes = [];
        }

        foreach ($keys as $key) {
            $this->clearCustomAttribute($key);
        }
    }

    /**
     * Determine if a custom attribute exists.
     *
     * @param  string  $key
     * @return bool
     */
    protected function hasCustomAttribute($key)
    {
        return array_key_exists($key, $this->customAttributes);
    }

    /**
     * Map a custom attribute system name.
     *
     * @param  string  $key
     * @return void
     */
    public static function mapCustomAttribute($key, $value)
    {
        static::$customAttributesMap[static::class][$key] = $value;
    }

    /**
     * Map a list of custom attribute system names.
     *
     * @param  array  $attributes
     * @return void
     */
    public static function mapCustomAttributes($attributes)
    {
        foreach ($attributes as $key => $value) {
            static::mapCustomAttribute($key, $value);
        }
    }

    /**
     * Unmap a custom attribute system name.
     *
     * @param  string  $key
     * @return void
     */
    public static function unmapCustomAttribute($key)
    {
        unset(static::$customAttributesMap[static::class][$key]);
    }

    /**
     * Unmap a list of custom attribute system names.
     *
     * @param  array  $keys
     * @return void
     */
    public static function unmapCustomAttributes($keys = [])
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        if (empty($keys)) {
            static::$customAttributesMap[static::class] = [];
        }

        foreach ($keys as $key) {
            static::unmapCustomAttribute($key);
        }
    }

    /**
     * Determine if a custom attribute is mapped.
     *
     * @param  string  $key
     * @return bool
     */
    protected static function hasAttributeMapped($key)
    {
        return  array_key_exists(static::class, static::$customAttributesMap) &&
                array_key_exists($key, static::$customAttributesMap[static::class]);
    }
}
