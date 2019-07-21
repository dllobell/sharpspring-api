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

use Dllobell\SharpspringApi\Concerns\HasCustomAttributes;

/**
 * Class ModelWithCustom
 *
 * Class for Sharpspring models that can have custom fields (Account, Lead, Opportunity)
 *
 * @package dllobell\sharpspring-api
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
abstract class ModelWithCustom extends Model
{
    use HasCustomAttributes;

    /**
     * Get all primary and custom attributes from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAllAttributes()
    {
        return array_merge(
            $this->getAttributes(),
            $this->getCustomAttributes()
        );
    }

    public function getAllFilledAttributes()
    {
        return array_merge(
            $this->getFilledAttributes(),
            $this->getCustomAttributes()
        );
    }

    /**
     * Get an attribute from the model.
     * Overrided from {@link Model} to implement recognition of custom attributes.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (static::hasAttributeMapped($key)) {
            $key = static::$customAttributesMap[static::class][$key];
        }

        if ($this->hasCustomAttribute($key)) {
            return $this->customAttributes[$key];
        }

        return parent::getAttribute($key);
    }

    /**
     * Set a given attribute on the model.
     * Overrided from {@link Model} to implement recognition of custom attributes.
     *
     * @param  string  $key
     * @param  mixed  $value
     */
    public function setAttribute($key, $value)
    {
        if ($this->hasAttribute($key)) {
            return $this->attributes[$key] = $value;
        }

        if (static::hasAttributeMapped($key)) {
            $key = static::$customAttributesMap[static::class][$key];
        }

        $this->customAttributes[$key] = $value;
    }

    /**
     * Clone the model into a new, non-existing instance.
     *
     * @return \Dllobell\SharpspringApi\Model
     */
    public function replicate()
    {
        $model = parent::replicate();
        $model->customAttributes = $this->customAttributes;

        return $model;
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getAllAttributes();
    }
}
