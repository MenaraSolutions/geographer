<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

/**
 * Class Ukrainian
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Ukrainian extends Base
{
    /**
     * @var string
     */
    protected $code = 'uk';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'з',
        'in' => 'в'
    ];
}
