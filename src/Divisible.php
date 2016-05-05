<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\MemberCollection;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;
use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;
use MenaraSolutions\FluentGeonames\Services\DefaultConfig;
use MenaraSolutions\FluentGeonames\Services\TranslationRepository;

/**
 * Class Divisible
 * @package App
 */
abstract class Divisible
{
    /**
     * @var \stdClass $meta
     */
    protected $meta;
    
    /**
     * @var MemberCollection $members
     */
    protected $members;

    /**
     * @var string $memberClass
     */
    protected $memberClass;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * Country constructor.
     * @param \stdClass $meta
     * @param ConfigInterface $config
     */
    public function __construct(\stdClass $meta = null, ConfigInterface $config = null)
    {
        $this->meta = $meta;
        $this->config = $config ?: new DefaultConfig();

        $this->loadMembers();
    }

    /**
     * @return string
     */
    abstract protected function getStoragePath();

    /**
     * @return MemberCollection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->config->setLanguage($language);
    }

    /**
     * @param MemberCollection $collection
     * @return void
     */
    protected function loadMembers(MemberCollection $collection = null)
    {
        $file = $this->getStoragePath();

        $collection = $collection ?: (new MemberCollection());

        if (file_exists($file)) {
            foreach(json_decode(file_get_contents($file)) as $meta) {
                $collection->add(new $this->memberClass($meta, $this->config));
            }
        }

        $this->members = $collection;
    }

    /**
     * @param $input
     * @param string|null $language
     * @return string
     */
    public function translate($input, $language = null)
    {
        $translator = $this->config->getTranslator();
        $language = $language ?: $this->config->getLanguage();

        return $translator->translate($input, get_class($this), $language);
    }
}