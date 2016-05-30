<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

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
     * @param string $string
     * @param string $form
     * @return string
     */
    public function preposition($string, $form)
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