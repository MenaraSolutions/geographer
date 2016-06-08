<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;

/**
 * Class Italian
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Italian extends Base
{
    /**
     * @var string
     */
    protected $code = 'it';

   /**
    * @var array
    */
    protected $defaultPrepositions = [
        'from' => 'da',
        'in' => 'in'
    ];
}
