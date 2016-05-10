<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\MemberCollection;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;
use MenaraSolutions\FluentGeonames\Contracts\IdentifiableInterface;
use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;
use MenaraSolutions\FluentGeonames\Services\DefaultConfig;
use MenaraSolutions\FluentGeonames\Services\TranslationRepository;
use MenaraSolutions\FluentGeonames\Traits\HasConfig;

/**
 * Class Divisible
 * @package App
 */
abstract class Divisible implements IdentifiableInterface
{
    use HasConfig;

    /**
     * @var \stdClass $meta
     */
    protected $meta;
    
    /**
     * @var MemberCollection $members
     */
    protected $members = null;

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
        if (! $this->members) $this->loadMembers();

        return $this->members;
    }

    /**
     * @param array $params
     * @return Divisible|bool
     */
    public function find(array $params = [])
    {
        if (! $this->members) $this->loadMembers();

        foreach($this->members as $member) {
            $memberArray = $member->toArray();
            $match = true;

            foreach ($params as $key => $value) {
                if (!isset($memberArray[$key]) || strcasecmp($memberArray[$key], $value) != 0) $match = false;
            }

            if ($match) return $member;
        }

        return false;
    }

    /**
     * @param MemberCollection $collection
     * @return void
     */
    protected function loadMembers(MemberCollection $collection = null)
    {
        $file = $this->getStoragePath();

        $collection = $collection ?: (new MemberCollection($this->config));

        if (file_exists($file)) {
            foreach(json_decode(file_get_contents($file)) as $meta) {
                $collection->add(new $this->memberClass($meta, $this->config));
            }
        }

        $this->members = $collection;
    }

    /**
     * @return string
     */
    abstract public function getLongName();

    /**
     * @return string
     */
    abstract public function getShortName();

    /**
     * Best effort name
     *
     * @return string
     */
    public function getName()
    {
        if (! $this->config->expectsLongNames()) return $this->getShortName();

        return $this->getLongName();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName()
        ];
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

        $translation = $translator->translate($this->config, $this, $language);
        return $translation ?: $input;
    }
}