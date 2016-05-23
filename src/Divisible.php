<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\MemberCollection;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;
use MenaraSolutions\FluentGeonames\Contracts\IdentifiableInterface;
use MenaraSolutions\FluentGeonames\Services\DefaultConfig;
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
     * @var Divisible
     */
    private $parent;

    /**
     * Country constructor.
     * @param \stdClass $meta
     * @param Divisible $parent
     * @param ConfigInterface $config
     */
    public function __construct(\stdClass $meta = null, Divisible $parent = null, ConfigInterface $config = null)
    {
        $this->meta = $meta;
        //$this->parent = $parent;
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
        foreach($this->getMembers() as $member) {
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
                $collection->add(new $this->memberClass($meta, $this, $this->config));
            }
        }

        $this->members = $collection;
    }

    /**
     * Best effort name
     *
     * @return string
     */
    public function getName()
    {
        return $this->config->expectsLongNames() ? $this->getLongName() : $this->getShortName();
    }


    /**
     * @return string
     */
    public function getShortName()
    {
        $this->config->useShortNames();

        return $this->translate();
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        $this->config->useLongNames();

        return $this->translate();
    }

    /**
     * @return bool
     */
    public function expectsLongNames()
    {
        return $this->config->expectsLongNames();
    }

    /**
     * @return Divisible
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @return \stdClass
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param string $form
     * @return $this
     */
    public function inflict($form)
    {
        $this->config->setForm($form);

        return $this;
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
     * @param string $language
     * @return string
     */
    public function translate($language = null)
    {
        if ($language) $this->config->setLanguage($language);

        return $this->config->getTranslator()
            ->translate($this, $this->config->getLanguage());
    }
}