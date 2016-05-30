<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;

/**
 * Class French
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class French extends BaseEuropean
{
    /**
     * @var string
     */
    protected $code = 'fr';

    /**
     * @param IdentifiableInterface $subject
     * @param $form
     * @return string
     */
    public function preposition(IdentifiableInterface $subject, $form)
    {
        switch ($form) {
            case 'from':
            case 'in':
            default:
                return '';
        }
    }
}