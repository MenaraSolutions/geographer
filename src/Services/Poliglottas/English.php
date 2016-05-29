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
     * @param array $meta
     * @return string
     */
    private function getLongName(array $meta)
    {
        return ! empty($meta['names']['long']) ? $meta['names']['long'] : $meta['names']['short'];
    }

    /**
     * @param array $meta
     * @return string
     */
    private function getShortName(array $meta)
    {
        return ! empty($meta['names']['short']) ? $meta['names']['short'] : $meta['names']['long'];
    }

    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @param string $prepositions
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $form = 'default', $prepositions = 'true')
    {
        return $subject->expectsLongNames() ? $this->getLongName($subject->getMeta()) : $this->getShortName($subject->getMeta());
    }
}