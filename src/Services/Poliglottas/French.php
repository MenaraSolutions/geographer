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
    * @var array
    */
    protected $defaultPrepositions = [
        'from' => 'de',
        'in' => 'en'
    ];
}
