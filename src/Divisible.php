<?php

namespace MenaraSolutions\Geographer;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Contracts\ConfigInterface;
use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Services\DefaultConfig;
use MenaraSolutions\Geographer\Traits\HasConfig;
use MenaraSolutions\Geographer\Repositories\File;

/**
 * Class Divisible
 * @package App
 */
abstract class Divisible implements IdentifiableInterface
{
    use HasConfig;

    /**
     * @var array $meta
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
     * @var string $parentClass
     */
    protected static $parentClass;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var Divisible
     */
    private $parent;

    /**
     * @var string
     */
    protected $parentCode;

    /**
     * Country constructor.
     * @param array $meta
     * @param string $parentCode
     * @param ConfigInterface $config
     */
    public function __construct(array $meta = [], $parentCode = null, ConfigInterface $config = null)
    {
        $this->meta = $meta;
        $this->parentCode = $parentCode;
        $this->config = $config ?: new DefaultConfig();
    }

    /**
     * @return MemberCollection
     */
    public function getMembers()
    {
        if (! $this->memberClass) return false;
        if (! $this->members) $this->loadMembers();

        return $this->members;
    }

    /**
     * @param Divisible $member
     * @param array $params
     * @return bool
     */
    private function match(Divisible $member, array $params)
    {
        $memberArray = $member->toArray();
        $match = true;

        foreach ($params as $key => $value) {
            if (!isset($memberArray[$key]) || strcasecmp($memberArray[$key], $value) != 0) $match = false;
        }

        return $match;
    }

    /**
     * @param array $params
     * @return MemberCollection
     */
    public function find(array $params = [])
    {
        $members = new MemberCollection($this->config);

        foreach($this->getMembers() as $member) {
            if ($this->match($member, $params)) {
                $members->add($member);
            }
        }

        return $members;
    }

    /**
     * @param array $params
     * @return Divisible|bool
     */
    public function findOne(array $params = [])
    {
        foreach($this->getMembers() as $member) {
            if ($this->match($member, $params)) return $member;
        }

        return false;
    }

    /**
     * @param MemberCollection $collection
     * @return void
     */
    protected function loadMembers(MemberCollection $collection = null)
    {
        $data = $this->config->getRepository()->getData(get_class($this), [
            'code' => $this->getCode(), 'parentCode' => $this->getParentCode()
        ]);

        $collection = $collection ?: (new MemberCollection($this->config));

        foreach($data as $meta) {
            $collection->add(new $this->memberClass($meta, $this->getCode(), $this->config));
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
        if (! $this->parent) {
            $this->parent = new static::$parentClass([], null, $this->config);
        }

        return $this->parent;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
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
     * @return string|int
     */
    abstract public function getCode();

    /**
     * @return string|int
     */
    public function getParentCode()
    {
        return $this->meta['parent'];
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

    /**
     * @param int|string $id
     * @param ConfigInterface $config
     * @return City
     */
    public static function build($id, $config = null)
    {
        $config = $config ?: new DefaultConfig();
        $meta = File::indexSearch($id, static::$parentClass, $config->getStoragePath());

        return new static($meta, $meta['parent'], $config);
    }
}
