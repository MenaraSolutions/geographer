<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

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
     * @param string $string
     * @param string $form
     * @return string
     */
    public function preposition($string, $form)
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