<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\PoliglottaInterface;

/**
 * Class English
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class English implements PoliglottaInterface
{
   /**
    * @var array
    */
    protected $defaultPrepositions = [
        'from' => 'from',
        'in' => 'in'
    ];

    /**
     * @param array $meta
     * @return string
     */
    private function getLongName(array $meta)
    {
        return ! empty($meta['names']['long']) ? $meta['names']['long'] : $meta['names']['short'];
    }

    /**
     * @param array $meta
     * @return string
     */
    private function getShortName(array $meta)
    {
        return ! empty($meta['names']['short']) ? $meta['names']['short'] : $meta['names']['long'];
    }

    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @param bool $preposition
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $form = 'default', $preposition = true)
    {
        if ($form != 'default' and !isset($this->defaultPrepositions[$form])) {
            throw new MisconfigurationException('Language ' . $this->code . ' doesn\'t inflict to ' . $form);
        }

	$result = $subject->expectsLongNames() ? $this->getLongName($subject->getMeta()) : $this->getShortName($subject->getMeta());

	if ($preposition && $form != 'default') {
	    $result = $this->defaultPrepositions[$form] . ' ' . $result;
	}

	return $result;
    }
}
