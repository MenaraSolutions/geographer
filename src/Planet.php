<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\DivisionCollection;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;
use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;
use MenaraSolutions\FluentGeonames\Services\DefaultConfig;
use MenaraSolutions\FluentGeonames\Services\TranslationRepository;
use MenaraSolutions\FluentGeonames\Traits\HasPublicFields;
use MenaraSolutions\FluentGeonames\Traits\HasTranslations;

class Planet extends Divisible
{
    use HasTranslations;

    /**
     * @var ConfigInterface $config
     */
    private $config;
    
    /**
     * @var string
     */
    protected $memberClass = Country::class;

    /**
     * Planet constructor.
     * @param ConfigInterface $config
     * @param TranslationRepositoryInterface $translator
     */
    public function __construct(ConfigInterface $config = null, TranslationRepositoryInterface $translator = null)
    {
        $this->config = $config ?: new DefaultConfig();
        $this->translator = $translator ?: new TranslationRepository();

        $this->loadMembers($translator);
    }

    /**
     * @return DivisionCollection
     */
    public function getCountries()
    {
        return $this->getMembers();
    }

    /**
     * @return string
     */
    protected function getStoragePath() {
        return $this->config->getStoragePath() . "countries.json";
    }
}