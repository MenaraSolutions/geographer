<?php

namespace MenaraSolutions\FluentGeonames\Services;

use MenaraSolutions\FluentGeonames\Contracts\IdentifiableInterface;
use MenaraSolutions\FluentGeonames\Contracts\PoliglottaInterface;
use MenaraSolutions\FluentGeonames\Contracts\TranslationAgencyInterface;
use MenaraSolutions\FluentGeonames\Country;
use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;
use MenaraSolutions\FluentGeonames\Services\Poliglottas\Russian;
use MenaraSolutions\FluentGeonames\Services\Poliglottas\English;
use MenaraSolutions\FluentGeonames\State;
use MenaraSolutions\FluentGeonames\Earth;
use MenaraSolutions\FluentGeonames\Exceptions\FileNotFoundException;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;

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
        
        return $this->getTranslator($language)->translateCountry($subject);
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