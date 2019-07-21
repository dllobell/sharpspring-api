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

use ArrayAccess;
use JsonSerializable;
use Dllobell\SharpspringApi\Contracts\Jsonable;
use Dllobell\SharpspringApi\Contracts\Arrayable;
use Dllobell\SharpspringApi\Concerns\HasAttributes;

/**
 * Class Model
 *
 * Base class for Sharpspring models
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
abstract class Model implements Arrayable, Jsonable, ArrayAccess, JsonSerializable
{
    use HasAttributes;

    /**
     * The model's primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The model's attributes that can be set null.
     *
     * @var array
     */
    protected $nullable = [];

    /**
     * Create a new Sharpspring model instance.
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->initializeAttributes();
        $this->setAttributes($attributes);
    }

    /**
     * Fills the attributes with null of a new model
     *
     * @return void
     */
    private function initializeAttributes()
    {
        $this->attributes = array_fill_keys(array_keys(array_flip($this->attributes)), null);
    }

    /**
     * Get all nullable attributes from the model.
     *
     * @return array
     */
    public function getNullableAttributes()
    {
        return array_intersect_key($this->attributes, array_flip($this->nullable));
    }

    /**
     * Get all non nullable attributes from the model.
     *
     * @return array
     */
    public function getNonNullableAttributes()
    {
        return array_diff_key($this->attributes, array_flip($this->nullable));
    }

    public function getFilledAttributes()
    {
        return array_merge(
            $this->getNullableAttributes(),
            array_filter(
                $this->getNonNullableAttributes(),
                function ($attribute) {
                    return $attribute !== null;
                }
            )
        );
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }

    /**
     * Set the value of the model's primary key.
     *
     * @param mixed $value
     */
    public function setKey($value)
    {
        $this->setAttribute($this->getKeyName(), $value);
    }

    /**
     * Clone the model into a new, non-existing instance.
     *
     * @return Model
     */
    public function replicate()
    {
        $model = new static;
        $model->attributes = $this->attributes;
        $model->setKey(null);

        return $model;
    }

    /**
     * Create a new model instance with the given attributes.
     *
     * @param  array $attributes
     *
     * @return Model
     */
    public static function make($attributes = [])
    {
        return new static($attributes);
    }

    /**
     * Create a new model instance with the given json string.
     *
     * @param  string $json
     *
     * @return Model
     */
    public static function makeFromJson($json)
    {
        return static::make(json_decode($json, true) ?: []);
    }

    /**
     * Create a new model instance with the given object.
     *
     * @param  object $object
     *
     * @return Model
     */
    public static function makeFromObject($object)
    {
        return static::make((array) $object);
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getAttributes();
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return ! is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->offsetSet($offset, null);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Determine if an attribute exists on the model.
     *
     * @param  string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
