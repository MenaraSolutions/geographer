<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\PoliglottaInterface;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;

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
     * @var string
     */
    protected $code = 'en';

    /**
     * @param array $meta
     * @return string
     */
    private function getLongName(array $meta)
    {
        return isset($meta['long']) ? $meta['long']['default'] : $meta['short']['default'];
    }

    /**
     * @param array $meta
     * @return string
     */
    private function getShortName(array $meta)
    {
        return isset($meta['short']) ? $meta['short']['default'] : $meta['long']['default'];
    }

    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @param bool $preposition
     * @return string
     * @throws MisconfigurationException
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
