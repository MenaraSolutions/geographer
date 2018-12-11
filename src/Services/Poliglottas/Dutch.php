<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;

/**
 * Class Dutch
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Dutch extends Base
{
    /**
     * @var string
     */
    protected $code = 'nl';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'van',
        'in' => 'in'
    ];
}
