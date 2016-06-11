<?php

namespace MenaraSolutions\Geographer\Traits;

use MenaraSolutions\Geographer\Exceptions\UnknownFieldException;

/**
 * Class ExposedFields
 */
trait ExposesFields
{
    /**
     * @return array
     */
    public function toArray()
    {
        $array = [
            'name' => $this->getName()
        ];

        foreach ($this->exposed as $key => $value) {
            $key = is_numeric($key) ? $value : $key;
            
            $array[$key] = $this->extract($value);
        }

        return $array;
    }

    /**
     * @param string $path
     * @return mixed
     */
    protected function extract($path)
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

            if (! array_key_exists($field, $this->exposed) && ! in_array($field, $this->exposed)) {
                throw new UnknownFieldException('Field ' . $field . ' does not exist');
            }

            return $this->extract(isset($this->exposed[$field]) ? $this->exposed[$field] : $field);
        }

        throw new UnknownFieldException('Unknown magic getter');
    }
}