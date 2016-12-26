<?php

namespace MenaraSolutions\Geographer;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Services\DefaultManager;

class Earth extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = Country::class;

    /**
     * @var null
     */
    protected static $parentClass = null;

    /**
     * @var array
     */
    protected $exposed = [
        'code',
        'name'
    ];

    /**
     * @return MemberCollection
     */
    public function getCountries()
    {
        return $this->getMembers();
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return 'Earth';
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return 'The Blue Marble';
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return 'SOL-III';
    }

    /**
     * @return null
     */
    public function getParentCode()
    {
        return null;
    }

    /**
     * @return MemberCollection
     */
    public function getAfrica()
    {
        return $this->find([
            'continent' => 'AF'
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getNorthAmerica()
    {
        return $this->find([
            'continent' => 'NA'
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getSouthAmerica()
    {
        return $this->find([
            'continent' => 'SA'
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getAsia()
    {
        return $this->find([
            'continent' => 'AS'
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getEurope()
    {
        return $this->find([
            'continent' => 'EU'
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getOceania()
    {
        return $this->find([
            'continent' => 'OC'
        ]);
    }

    /**
     * @return static
     */
    public function withoutMicro()
    {
        return $this->getMembers()->filter(function($item) {
            return $item->getPopulation() > 100000;
        });
    }

    /**
     * @inheritdoc
     */
    public static function build($id, $config = null)
    {
        $config = $config ?: new DefaultManager();
        return new static([], null, $config);
    }
}