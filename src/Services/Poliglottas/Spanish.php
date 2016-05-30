<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;

/**
 * Class Spanish
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Spanish extends BaseEuropean
{
    /**
     * @var string
     */
    protected $code = 'es';

    /**
     * @param IdentifiableInterface $subject
     * @param $form
     * @return string
     */
    public function preposition(IdentifiableInterface $subject, $form)
    {
        switch ($form) {
            case 'from':
                return 'de';

            case 'in':
                return 'en';

            default:
                return '';
        }
    }
}