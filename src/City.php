<?php

namespace MenaraSolutions\Geographer;

use MenaraSolutions\Geographer\Exceptions\FileNotFoundException;
use MenaraSolutions\Geographer\Exceptions\ObjectNotFoundException;
use MenaraSolutions\Geographer\Services\DefaultConfig;
use MenaraSolutions\Geographer\Contracts\ConfigInterface;

/**
 * Class City
 * @package MenaraSolutions\Geographer
 */
class City extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = null;

    /**
     * @var string
     */
    protected $parentClass = State::class;

    /**
     * @return string
     */
    protected function getStoragePath()
    {
	    return '';
    }

    /**
     * Unique code
     *
     * @return int
     */
    public function getCode()
    {
	return $this->meta['ids']['geonames'];
    }

    /**
     * @return int
     */
    public function getGeonamesCode()
    {
	    return $this->getCode();
    }

    /**
     * @param int $geonamesId
     * @param ConfigInterface $config
     * @return City
     */
    public static function build($geonamesId, $config = null)
    {
        $config = $config ?: new DefaultConfig();
        $meta = [];
        $parentCode = static::indexSearch($geonamesId, $config->getStoragePath() . 'indexCity.json');
        
        return new self($meta, $parentCode, $config);
    }

    /**
     * @param int $geonamesId
     * @param string $path
     * @return array
     * @throws ObjectNotFoundException
     * @throws FileNotFoundException
     */
    public static function indexSearch($geonamesId, $path)
    {
        if (! file_exists($path)) throw new FileNotFoundException('Index file not found');
        $index = json_decode(file_get_contents($path), true);

        if (! isset($index[$geonamesId])) throw new ObjectNotFoundException('Cannot find object with id ' . $geonamesId);
        
        return $index[$geonamesId];
    }
}
