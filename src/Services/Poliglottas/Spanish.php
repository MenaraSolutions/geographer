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
    * @var array
    */
    protected $defaultPrepositions = [
        'from' => 'de',
        'in' => 'en'
    ];
}
