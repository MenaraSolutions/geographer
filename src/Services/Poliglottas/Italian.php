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
    * @var array
    */
    protected $defaultPrepositions = [
        'from' => 'di',
        'in' => 'in'
    ];
}
