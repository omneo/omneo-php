<?php

namespace Omneo\Concerns;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

trait HasAttributes
{
    /**
     * Array of dirty attributes.
     *
     * @var array
     */
    protected $dirtyAttributes = [];

    /**
     * Get an attribute from the container.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = parent::get($key, $default);

        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        return $value;
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return method_exists($this, 'get'.Str::studly($key).'Attribute');
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get'.Str::studly($key).'Attribute'}($value);
    }

    /**
     * Set the attributes to the container.
     *
     * @param  array  $attributes
     * @return static
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $offset
     * @param  mixed   $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        parent::offsetSet($offset, $value);

        if (! in_array($offset, $this->dirtyAttributes)) {
            array_push($this->dirtyAttributes, $offset);
        }
    }

    /**
     * Set dirty attributes.
     *
     * @param  array  $dirtyAttributes
     * @return static
     */
    public function setDirtyAttributes(array $dirtyAttributes)
    {
        $this->dirtyAttributes = $dirtyAttributes;
    }

    /**
     * Get dirty attributes.
     *
     * @return array
     */
    public function getDirtyAttributes()
    {
        return $this->dirtyAttributes;
    }

    /**
     * Get dirty attribute values.
     *
     * @return array
     */
    public function getDirtyAttributeValues()
    {
        return Arr::only($this->attributes, $this->dirtyAttributes);
    }
}