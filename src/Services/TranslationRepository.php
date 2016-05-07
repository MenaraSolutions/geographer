<?php

namespace MenaraSolutions\FluentGeonames\Services;

use MenaraSolutions\FluentGeonames\Contracts\IdentifiableInterface;
use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;
use MenaraSolutions\FluentGeonames\Country;
use MenaraSolutions\FluentGeonames\State;
use MenaraSolutions\FluentGeonames\Planet;

/**
 * Class TranslationRepository
 * @package MenaraSolutions\FluentGeonames\Services
 */
class TranslationRepository implements TranslationRepositoryInterface
{
    /**
     * @var string
     */
    const DEFAULT_LANGUAGE = 'en';

    /**
     * @var string
     */
    protected $base_path;

    /**
     * @var array
     */
    protected $prefixes = [
        Country::class => 'country',
        State::class => 'state',
        Planet::class => 'planet'
    ];

    /**
     * TranslationRepository constructor.
     * @param $base_path
     */
    public function __construct($base_path)
    {
        $this->base_path = $base_path;
    }

    /**
     * @param string $input
     * @param IdentifiableInterface $subject
     * @param string $language
     * @return string
     */
    public function translate($input, IdentifiableInterface $subject, $language)
    {
        // English is the source language
        if ($language == self::DEFAULT_LANGUAGE) return $input;

        $this->loadTranslations($subject, $language);
    }

    /**
     * @param string $class
     * @param string|int $id
     * @param $language
     * @return string|bool
     */
    public function getStoragePath($class, $id, $language)
    {
        if (! array_key_exists($class, $this->prefixes)) return false;

        return $this->base_path . 'translations/' . $this->prefixes[$class] . '-' . $id . '-' . $language . '.json';
    }

    /**
     * @param IdentifiableInterface $subject
     * @param $language
     */
    public function loadTranslations(IdentifiableInterface $subject, $language)
    {
        $source = $this->getStoragePath(get_class($subject), $subject->getCode(), $language);

        if (!file_exists($source)) return false;
    }
}