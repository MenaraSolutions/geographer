<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\TranslationAgencyInterface;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\State;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;
use MenaraSolutions\Geographer\Exceptions\FileNotFoundException;
use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\PoliglottaInterface;
use MenaraSolutions\Geographer\City;

/**
 * Class Base
 * @package App\Services\Poliglottas
 */
abstract class Base implements PoliglottaInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var TranslationAgencyInterface
     */
    protected $agency;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var array
     */
    protected $defaultPrepositions = [];

    /**
     * Base constructor.
     * @param TranslationAgencyInterface $agency
     */
    public function __construct(TranslationAgencyInterface $agency)
    {
        $this->agency = $agency;
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
        if (! method_exists($this, 'inflict' . ucfirst($form))) {
            throw new MisconfigurationException('Language ' . $this->code . ' doesn\'t inflict to ' . $form);
        }

        $meta = $this->fromDictionary($subject);
        $result = $this->extract($meta, $subject->expectsLongNames(), $form, true);

	    if ($result && $preposition) return $result;
	    if ($result && ! $preposition) return mb_substr($result, mb_strpos($result, ' '));

        $result = $this->inflictDefault($meta, $subject->expectsLongNames());
	    if ($form == 'default') return $result;

	    $result = $this->{'inflict' . ucfirst($form)}($result);
        if ($preposition) $result = $this->getPreposition($form, $result) . ' ' . $result;

        return $result;
    }

    /**
     * @param IdentifiableInterface $subject
     * @return array
     */
    protected function fromDictionary(IdentifiableInterface $subject)
    {
        try {
            $translations = $this->agency->getRepository()->getTranslations($subject, $this->code);
        } catch (FileNotFoundException $e) {
            return $subject->getMeta();
        }

        return $translations ?: $subject->getMeta();
    }

    /**
     * @param array $meta
     * @param $long
     * @return string
     */
    protected function inflictDefault(array $meta, $long)
    {
        return $this->extract($meta, $long, 'default', true);
    }

    /**
     * @param string $template
     * @return string
     */
    protected function inflictIn($template)
    {
	    return $template;
    }

    /**
     * @param string $template
     * @return string
     */
    protected function inflictFrom($template)
    {   
        return $template;
    }

    /**
     * @param $form
     * @param string $result
     * @return string
     */
    protected function getPreposition($form, $result = null)
    {
        return $this->defaultPrepositions[$form];
    }

    /**
     * @param array $meta
     * @param bool $long
     * @param $form
     * @param bool $fallback
     * @return mixed
     */
    protected function extract(array $meta, $long, $form, $fallback = false)
    {
        $variants = [];
        $keys = $long ? [ 'long', 'short '] : [ 'short', 'long' ];

	    if (! isset($meta[$keys[0]][$form]) && ! $fallback) return false;

        if (isset($meta[$keys[0]][$form])) {
            $variants[] = $meta[$keys[0]][$form];
        }

        if (isset($meta[$keys[1]][$form])) {
            $variants[] = $meta[$keys[1]][$form];
        }

        return !empty($variants) ? $variants[0] : false;
    }
}
