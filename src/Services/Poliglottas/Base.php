<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\State;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;
use MenaraSolutions\Geographer\Exceptions\FileNotFoundException;
use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\PoliglottaInterface;
use MenaraSolutions\Geographer\City;

/**
 * Class Base
 * @package App\Services\Poliglottas
 */
abstract class Base implements PoliglottaInterface
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $code;
    
    /**
     * @var array
     */
    protected $prefixes = [
        Earth::class => 'planet',
        Country::class => 'country',
        State::class => 'state',
        City::class => 'city'
    ];

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var array
     */
    protected $defaultPrepositions = [];

    /**
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @param bool $preposition
     * @return string
     * @throws MisconfigurationException
     */
    public function translate(IdentifiableInterface $subject, $form = 'default', $preposition = true)
    {   
        if (! method_exists($this, 'inflict' . ucfirst($form))) {
            throw new MisconfigurationException('Language ' . $this->code . ' doesn\'t inflict to ' . $form);
        }

        $meta = $this->fromCache($subject);
        $result = $this->extract($meta, $subject->expectsLongNames(), $form);

        if (! $result) {
            $template = $this->inflictDefault($meta, $subject->expectsLongNames());
            $result = $this->{'inflict' . ucfirst($form)}($template);
            
            if ($preposition) $result = $this->defaultPrepositions[$form] . ' ' . $result;
        } else if ($result && ! $preposition) {
            $result = mb_substr($result, mb_strpos($result, ' '));
        }

        return $result;
    }

    /**
     * @param $class
     * @throws MisconfigurationException
     * @return string
     */
    protected function getPrefix($class)
    {
        if (! array_key_exists($class, $this->prefixes)) throw new MisconfigurationException('Unsupported class');

        return $this->prefixes[$class];
    }

    /**
     * @param string $class
     * @param string|int $memberId
     * @return string|bool
     */
    public function getStoragePath($class, $memberId)
    {
        switch ($class) {
	        case State::class:
            case Country::class:
                return $this->basePath . 'translations/' . $this->getPrefix($class) . DIRECTORY_SEPARATOR . $this->code . '.json';

                break;

            default:
        }
    }

    /**
     * @param IdentifiableInterface $subject
     * @throws FileNotFoundException
     */
    public function loadDictionaries(IdentifiableInterface $subject)
    {
        if (isset($this->cache[$this->getPrefix(get_class($subject))][$this->code])) return;

        $source = $this->getStoragePath(get_class($subject), $subject->getCode());
        if (!file_exists($source)) throw new FileNotFoundException('File not found: ' . $source);

        foreach (json_decode(file_get_contents($source), true) as $one) {
            $this->cache[$this->getPrefix(get_class($subject))][$this->code][$one['code']] = $one;
        }
    }

    /**
     * @param IdentifiableInterface $subject
     * @return array
     */
    protected function fromCache(IdentifiableInterface $subject)
    {
        $this->loadDictionaries($subject);

        $meta = isset($this->cache[$this->getPrefix(get_class($subject))][$this->code][$subject->getCode()]) ?
            $this->cache[$this->getPrefix(get_class($subject))][$this->code][$subject->getCode()] : $subject->getMeta();

        return $meta;
    }

    /**
     * @param array $meta
     * @param $long
     * @return string
     */
    protected function inflictDefault(array $meta, $long)
    {
        return $this->extract($meta, $long, 'default');
    }

    /**
     * @param string $template
     * @return string
     */
    protected function inflictIn($template)
    {
	    return $template;
    }

    /**
     * @param string $template
     * @return string
     */
    protected function inflictFrom($template)
    {   
        return $template;
    }
   
    /**
     * @param array $meta
     * @param $long
     * @param $form
     * @return mixed
     */
    protected function extract(array $meta, $long, $form)
    {
        $variants = [];
       
        if (isset($meta['long'][$form])) {
            $variants[] = $meta['long'][$form];
        }
    
        if (isset($meta['short'][$form])) {
            $variants[] = $meta['short'][$form];
        }
        
        if (! $long) $variants = array_reverse($variants);
         
        return !empty($variants) ? $variants[0] : false;
    }
}
