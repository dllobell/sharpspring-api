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
 * Trait HasAttributes
 *
 * Adds attributes functionality to Sharpspring models.
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
trait HasAttributes
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if ($this->hasAttribute($key)) {
            return $this->attributes[$key];
        }
    }

    /**
     * Get all attributes from the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function setAttribute($key, $value)
    {
        if ($this->hasAttribute($key)) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Set a list of attributes on the model.
     *
     * @param  array $attributes
     */
    public function setAttributes($attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Determine if an attribute exists.
     *
     * @param  string $key
     *
     * @return bool
     */
    protected function hasAttribute($key)
    {
        return array_key_exists($key, $this->attributes);
    }
}
