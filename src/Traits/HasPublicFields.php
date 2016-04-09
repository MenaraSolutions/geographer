<?php

namespace MenaraSolutions\FluentGeonames\Traits;

use MenaraSolutions\FluentGeonames\Exceptions\UnknownFieldException;

/**
 * Class HasPublicFields
 * @package MenaraSolutions\FluentGeonames\Traits
 */
trait HasPublicFields
{
    /**
     * @param $methodName
     * @param $args
     * @return string|array
     * @throws \MenaraSolutions\FluentGeonames\Exceptions\UnknownFieldException
     */
    public function __call($methodName, $args)
    {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];

            if (!property_exists($this, $property) || !in_array($property, $this->public)) {
                throw new UnknownFieldException();
            }

            switch ($matches[1]) {
                case 'set':
                    $this->{$property} = $args[0];

                    return $this;

                case 'get':
                    return $this->{$property};

                case 'default':
            }
        }
    }
}