<?php


namespace Code\Core;


class Container
{
    private $dependencies = [];

    public function set($key, $value)
    {
        $this->dependencies[$key] = $value;
        return $this;
    }

    public function get($key)
    {
        $value = $this->dependencies[$key];

        if (is_callable($value)) {
            $function = $value;

            return $function($this);
        }
        return $value;
    }

}
