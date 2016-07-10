<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

/**
 * Class Mandarin
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Mandarin extends Base
{
    /**
     * @var string
     */
    protected $code = 'zh';

   /**
    * @var array
    */
    protected $defaultPrepositions = [
        'from' => '来自',
        'in' => '在'
    ];
}
