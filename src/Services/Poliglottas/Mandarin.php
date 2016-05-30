<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

/**
 * Class Mandarin
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Mandarin extends BaseEuropean
{
    /**
     * @var string
     */
    protected $code = 'zh';

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