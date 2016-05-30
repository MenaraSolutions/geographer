<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;

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