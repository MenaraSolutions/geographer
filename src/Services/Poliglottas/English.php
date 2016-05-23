<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\PoliglottaInterface;

/**
 * Class English
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class English implements PoliglottaInterface
{
    /**
     * @param \stdClass $meta
     * @return string
     */
    private function getLongName(\stdClass $meta)
    {
        return ! empty($meta->names->long) ? $meta->names->long : $meta->names->short;
    }

    /**
     * @param \stdClass $meta
     * @return string
     */
    private function getShortName(\stdClass $meta)
    {
        return ! empty($meta->names->short) ? $meta->names->short : $meta->names->long;
    }

    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $form)
    {
        return $subject->expectsLongNames() ? $this->getLongName($subject->getMeta()) : $this->getShortName($subject->getMeta());
    }
}