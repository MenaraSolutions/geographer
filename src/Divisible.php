<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\DivisionCollection;
use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;
use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;

/**
 * Class Divisible
 * @package App
 */
abstract class Divisible
{
    /**
     * @var \stdClass $meta
     */
    protected $meta;
    
    /**
     * @var DivisionCollection $members
     */
    protected $members;

    /**
     * @var string $memberClass
     */
    protected $memberClass;

    /**
     * @var TranslationRepositoryInterface
     */
    protected $translator;

    /**
     * @return string
     */
    abstract protected function getStoragePath();

    /**
     * @return DivisionCollection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param DivisionCollection $collection
     * @return void
     */
    protected function loadMembers(DivisionCollection $collection = null)
    {
        $file = $this->getStoragePath();

        $collection = $collection ?: (new DivisionCollection());

        if (file_exists($file)) {
            foreach(json_decode(file_get_contents($file)) as $meta) {
                $collection->add(new $this->memberClass($meta, $this->translator));
            }
        }

        $this->members = $collection;
    }
}