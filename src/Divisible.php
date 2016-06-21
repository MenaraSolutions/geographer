<?php

namespace MenaraSolutions\Geographer;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Contracts\ManagerInterface;
use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Services\DefaultManager;
use MenaraSolutions\Geographer\Traits\ExposesFields;
use MenaraSolutions\Geographer\Traits\HasManager;
use MenaraSolutions\Geographer\Repositories\File;

/**
 * Class Divisible
 * @package App
 */
abstract class Divisible implements IdentifiableInterface, \ArrayAccess
{
    use HasManager, ExposesFields;

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
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @var Divisible
     */
    private $parent;

    /**
     * @var string
     */
    protected $parentCode;

    /**
     * @var array
     */
    protected $exposed = [];

    /**
     * Country constructor.
     * @param array $meta
     * @param string $parentCode
     * @param ManagerInterface $manager
     */
    public function __construct(array $meta = [], $parentCode = null, ManagerInterface $manager = null)
    {
        $this->meta = $meta;
        $this->parentCode = $parentCode;
        $this->manager = $manager ?: new DefaultManager();
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
        $members = new MemberCollection($this->manager);

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
        return $this->find($params)->first();
    }

    /**
     * @param MemberCollection $collection
     * @return void
     */
    protected function loadMembers(MemberCollection $collection = null)
    {
        $data = $this->manager->getRepository()->getData(get_class($this), [
            'code' => $this->getCode(), 'parentCode' => $this->getParentCode()
        ]);

        $collection = $collection ?: (new MemberCollection($this->manager));

        foreach($data as $meta) {
            $collection->add(new $this->memberClass($meta, $this->getCode(), $this->manager));
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
        return $this->manager->expectsLongNames() ? $this->getLongName() : $this->getShortName();
    }


    /**
     * @return string
     */
    public function getShortName()
    {
        $this->manager->useShortNames();

        return $this->translate();
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        $this->manager->useLongNames();

        return $this->translate();
    }

    /**
     * @return bool
     */
    public function expectsLongNames()
    {
        return $this->manager->expectsLongNames();
    }

    /**
     * @return Divisible
     */
    public function parent()
    {
        if (! $this->parent) {
            $this->parent = call_user_func([static::$parentClass, 'build'], $this->parentCode, $this->manager);
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
        if ($language) $this->manager->setLanguage($language);

        return $this->manager->getTranslator()
            ->translate($this, $this->manager->getLanguage());
    }
    
    /**
     * @param int|string $id
     * @param ManagerInterface $config
     * @return City
     */
    public static function build($id, $config = null)
    {
        $config = $config ?: new DefaultManager();
        $meta = $config->getRepository()
            ->indexSearch($id, static::$parentClass);
        $parent = isset($meta['parent']) ? $meta['parent'] : null;

        return new static($meta, $parent, $config);
    }
}
