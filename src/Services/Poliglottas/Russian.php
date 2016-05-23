<?php

namespace MenaraSolutions\FluentGeonames\Services\Poliglottas;

use MenaraSolutions\FluentGeonames\Contracts\IdentifiableInterface;
use MenaraSolutions\FluentGeonames\Contracts\PoliglottaInterface;

/**
 * Class Russian
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Russian extends Base implements PoliglottaInterface
{
    /**
     * @var string
     */
    protected $code = 'ru';

    /**
     * @var array
     */
    protected $inflictsTo = ['from', 'in'];

    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $form)
    {
        $this->loadDictionaries($subject);

        $field = $subject->expectsLongNames() ? 'long' : 'short';
        $backupField = ! $subject->expectsLongNames() ? 'long' : 'short';
        $meta = $this->cache[$this->getPrefix(get_class($subject))][$this->code][$subject->getCode()];

        return $meta[$field]['default'] ?: $meta[$backupField]['default'];
    }
}