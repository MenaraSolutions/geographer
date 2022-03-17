<?php

namespace MenaraSolutions\Geographer\Traits;

use MenaraSolutions\Geographer\Exceptions\UnknownFieldException;

/**
 * Class ExposedFields
 */
trait ExposesFields
{
    /**
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->exposed[$offset]);
    }


    /**
     * @param mixed $offset
     *
     * @return mixed
     * @throws UnknownFieldException
     */
    public function offsetGet($offset): mixed
    {
        if (is_string($offset)) {
            return $this->__get($offset);
        }
    }

    /**
     * @param mixed $offset <p>
     * @param mixed $value <p>
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        return;
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        return;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this->exposed as $key => $value) {
            $key = is_numeric($key) ? $value : $key;

            $array[$key] = $this->__get($value);
        }

        return $array;
    }

    /**
     * @param string $path
     * @return mixed
     */
    protected function extract($path): mixed
    {
        $parts = explode('.', $path);

        if (count($parts) == 1) {
            return isset($this->meta[$path]) ? $this->meta[$path] : null;
        }

        $current = &$this->meta;

        foreach ($parts as $field) {
            if (! isset($current[$field])) {
                return null;
            }

            $current = &$current[$field];
        }

        return $current;
    }

    /**
     * @param $methodName
     * @param $args
     * @return string|int
     * @throws UnknownFieldException
     */
    public function __call($methodName, $args)
    {
        if (preg_match('~^(get)([A-Z])(.*)$~', $methodName, $matches)) {
            $field = strtolower($matches[2]) . $matches[3];

            return $this->__get($field);
        }

        if (preg_match('~^(findOneBy)([A-Z])(.*)$~', $methodName, $matches)) {
            $field = strtolower($matches[2]) . $matches[3];

            return $this->findOne([$field => $args[0]]);
        }

        throw new UnknownFieldException('Method ' . $methodName . ' doesn\'t exist');
    }

    /**
     * @param $field
     * @return string
     * @throws UnknownFieldException
     */
    public function __get($field)
    {
        if (! array_key_exists($field, $this->exposed) && ! in_array($field, $this->exposed)) {
            throw new UnknownFieldException('Field ' . $field . ' does not exist');
        }

        if (method_exists($this, 'get' . ucfirst($field))) {
            return call_user_func([$this, 'get' . ucfirst($field)]);
        }

        return $this->extract(isset($this->exposed[$field]) ? $this->exposed[$field] : $field);
    }

    /**
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
