<?php

namespace MenaraSolutions\Geographer;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Contracts\ManagerInterface;
use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Services\DefaultManager;
use MenaraSolutions\Geographer\Traits\ExposesFields;
use MenaraSolutions\Geographer\Traits\HasManager;
use MenaraSolutions\Geographer\Traits\HasCollection;

/**
 * Class Divisible
 * @package App
 */
abstract class Divisible implements IdentifiableInterface, \ArrayAccess
{
    use HasManager, ExposesFields, HasCollection;

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
     * @var string
     */
    protected $standard;

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
        if (! $this->members) $this->loadMembers();

        return $this->members;
    }
    
    /**
     * @param MemberCollection $collection
     * @return void
     */
    protected function loadMembers(MemberCollection $collection = null)
    {
        $standard = $this->standard ?: $this->manager->getStandard();

        $data = $this->manager->getRepository()->getData(get_class($this), [
            'code' => $this->getCode(), 'parentCode' => $this->getParentCode()
        ]);

        $collection = $collection ?: (new MemberCollection($this->manager));

        foreach($data as $meta) {
            $entity = new $this->memberClass($meta, $this->getCode(), $this->manager);

            if (! empty($entity[$standard . 'Code'])) $collection->add($entity, $entity[$standard . 'Code']);
        }

        $this->members = $collection;
    }

    /**
     * Best effort name
     *
     * @param string $locale
     * @return string
     */
    public function getName($locale = null)
    {
        if ($locale) $this->setLocale($locale);

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
     * @param string $locale
     * @return string
     */
    public function translate($locale = null)
    {
        if ($locale) $this->manager->setLocale($locale);

        return $this->manager->getTranslator()
            ->translate($this, $this->manager->getLocale());
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

    /**
     * @return array
     */
    public function getCodes()
    {
        $codes = [];
        array_walk_recursive($this->meta['ids'], function($id) use (&$codes) { $codes[] = $id; });

        return $codes;
    }
}
