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
    * @var array
    */
    protected $defaultPrepositions = [
        'from' => 'de',
        'in' => 'en'
    ];
}
