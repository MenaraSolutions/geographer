<?php

namespace MenaraSolutions\FluentGeonames\Services\Poliglottas;

use MenaraSolutions\FluentGeonames\Contracts\IdentifiableInterface;
use MenaraSolutions\FluentGeonames\Contracts\PoliglottaInterface;

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
     * @return string
     */
    public function translateCountry(IdentifiableInterface $subject)
    {
        return $subject->expectsLongNames() ? $this->getLongName($subject->getMeta()) : $this->getShortName($subject->getMeta());
    }

    /**
     * @param IdentifiableInterface $subject
     * @return string
     */
    public function translateState(IdentifiableInterface $subject)
    {
        return $subject->expectsLongNames() ? $this->getLongName($subject->getMeta()) : $this->getShortName($subject->getMeta());
    }

    /**
     * @param IdentifiableInterface $subject
     * @return string
     */
    public function translateCity(IdentifiableInterface $subject)
    {
        return $subject->expectsLongNames() ? $this->getLongName($subject->getMeta()) : $this->getShortName($subject->getMeta());
    }
}