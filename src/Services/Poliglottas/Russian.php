<?php

namespace MenaraSolutions\FluentGeonames\Services\Poliglottas;

use MenaraSolutions\FluentGeonames\Contracts\IdentifiableInterface;
use MenaraSolutions\FluentGeonames\Contracts\PoliglottaInterface;
use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;

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
     * @throws MisconfigurationException
     */
    public function translate(IdentifiableInterface $subject, $form = '')
    {
        if (! empty($form) && ! in_array($form, $this->inflictsTo)) {
            throw new MisconfigurationException('Language ' . $this->code . ' doesn\'t inflict to ' . $form);
        }

        $this->loadDictionaries($subject);

        $field = $subject->expectsLongNames() ? 'long' : 'short';
        $backupField = ! $subject->expectsLongNames() ? 'long' : 'short';
        $meta = $this->fromCache($subject);

        return $meta[$field]['default'] ?: $meta[$backupField]['default'];
    }
}