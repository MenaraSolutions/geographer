<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\PoliglottaInterface;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;

/**
 * Class Ukraine
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Ukraine extends Base
{
    /**
     * @var string
     */
    protected $code = 'uk';

   /**
    * @var array
    */
    protected $defaultPrepositions = [
        'from' => 'із',
        'in' => 'в'
    ];
}
