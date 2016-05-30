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
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $form = 'default')
    {
        return $subject->expectsLongNames() ? $this->getLongName($subject->getMeta()) : $this->getShortName($subject->getMeta());
    }

    /**
     * @param IdentifiableInterface $subject
     * @param $form
     * @return string
     */
    public function preposition(IdentifiableInterface $subject, $form)
    {
        switch ($form) {
            case 'from':
                return 'from';

            case 'in':
                return 'in';

            default:
                return '';
        }
    }
}