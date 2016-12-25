<?php

namespace MenaraSolutions\Geographer;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Services\DefaultManager;

/**
 * Class Country
 * @package MenaraSolutions\FluentGeonames
 */
class Country extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = State::class;

    /**
     * @var string
     */
    protected static $parentClass = Earth::class;

    /**
     * @var array
     */
    protected $exposed = [
        'code' => 'ids.iso_3166_1.0',
        'code3' => 'ids.iso_3166_1.1',
        'isoCode' => 'ids.iso_3166_1.0',
        'numericCode' => 'ids.iso_3166_1.2',
        'geonamesCode' => 'ids.geonames',
        'fipsCode' => 'ids.fips',
        'area',
        'currency',
        'phonePrefix' => 'phone',
        'population',
        'continent',
        'language' => 'languages.0',
        'name'
    ];

    /**
     * @return string
     */
    public function getParentCode()
    {
        return 'SOL-III';
    }

    /**
     * @return bool|Divisible
     */
    public function getCapital()
    {
        return $this->findOne([
            'capital' => true
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getStates()
    {
        return $this->getMembers();
    }

    /**
     * @inheritdoc
     */
    public static function build($id, $config = null)
    {
        $config = $config ?: new DefaultManager();
        $earth = (new Earth())->setManager($config);

        return $earth->findOneByCode($id);
    }
}