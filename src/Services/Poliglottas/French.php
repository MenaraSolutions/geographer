<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

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
     * @param string $string
     * @param string $form
     * @return string
     */
    public function preposition($string, $form)
    {
        switch ($form) {
            case 'from':
            case 'in':
            default:
                return '';
        }
    }
}