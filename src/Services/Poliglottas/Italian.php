<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;

/**
 * Class Italian
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Italian extends BaseEuropean
{
    /**
     * @var string
     */
    protected $code = 'it';

    /**
     * @param IdentifiableInterface $subject
     * @param $form
     * @return string
     */
    public function preposition(IdentifiableInterface $subject, $form)
    {
        switch ($form) {
            case 'from':
                return 'di';

            case 'in':
                return 'in';

            default:
                return '';
        }
    }
}