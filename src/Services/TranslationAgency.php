<?php

namespace MenaraSolutions\Geographer\Services;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\PoliglottaInterface;
use MenaraSolutions\Geographer\Contracts\TranslationAgencyInterface;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;
use MenaraSolutions\Geographer\Services\Poliglottas\Russian;
use MenaraSolutions\Geographer\Services\Poliglottas\English;

/**
 * Class TranslationAgency
 * @package MenaraSolutions\FluentGeonames\Services
 */
class TranslationAgency implements TranslationAgencyInterface
{
    /**
     * @var string
     */
    protected $base_path;

    /**
     * @var string
     */
    protected $form = 'default';

    /**
     * @var array
     */
    protected $inflictsTo = [];

    /**
     * @var bool
     */
    protected $prepositions = true;

    /**
     * List of available translators
     *
     * @var array
     */
    protected $languages = [
        'ru' => Russian::class,
        'en' => English::class
    ];

    /**
     * @var array PoliglottaInterface
     */
    protected $translators = [];
    
    /**
     * TranslationRepository constructor.
     * @param string $base_path
     */
    public function __construct($base_path)
    {
        $this->base_path = $base_path;
    }

    /**
     * @param string $form
     * @return $this
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return $this
     */
    public function includePrepositions()
    {
        $this->prepositions = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function excludePrepositions()
    {
        $this->prepositions = false;

        return $this;
    }

    /**
     * @param IdentifiableInterface $subject
     * @param string $language
     * @return string
     * @throws MisconfigurationException
     */
    public function translate(IdentifiableInterface $subject, $language = 'en')
    {
        if (! isset($this->languages[$language])) {
            throw new MisconfigurationException('No hablo ' . $language . ', sorry');
        }
        
        return $this->getTranslator($language)->translate($subject, $this->form, $this->prepositions);
    }

    /**
     * @param string $language
     * @return PoliglottaInterface
     */
    public function getTranslator($language)
    {
        if (! isset($this->translators[$language])) {
            $this->translators[$language] = new $this->languages[$language]($this->base_path);
        }

        return $this->translators[$language];
    }
    
    /**
     * @return array
     */
    public function getSupportedLanguages()
    {
        return array_keys($this->translators);
    }
}