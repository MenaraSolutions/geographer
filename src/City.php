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
        $meta = static::indexSearch($geonamesId, $config->getStoragePath());

        return new self($meta, $meta['parent'], $config);
    }

    /**
     * @param int $geonamesId
     * @param string $path
     * @return array
     * @throws ObjectNotFoundException
     */
    public static function indexSearch($geonamesId, $path)
    {
        $index = static::loadJson($path . 'indexCity.json');
        if (! isset($index[$geonamesId])) throw new ObjectNotFoundException('Cannot find object with id ' . $geonamesId);
	    $country = $index[$geonamesId];
        
	    $cities = static::loadJson($path . 'cities' . DIRECTORY_SEPARATOR . $country . '.json');

	    foreach ($cities as $city) {
		    if ($city['ids']['geonames'] == $geonamesId) return $city;
    	}

	    throw new ObjectNotFoundException('Cannot find meta for city #' . $geonamesId);
    }

    /**
     * @param string $path
     * @return array
     * @throws ObjectNotFoundException
     * @throws FileNotFoundException
     */
    protected static function loadJson($path)
    {
        if (! file_exists($path)) throw new FileNotFoundException('File not found: ' . $path);
        return json_decode(file_get_contents($path), true);
    }
}
