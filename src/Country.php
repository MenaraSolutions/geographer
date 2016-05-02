<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;
use MenaraSolutions\FluentGeonames\Traits\HasPublicFields;
use MenaraSolutions\FluentGeonames\Traits\HasTranslations;
use MenaraSolutions\FluentGeonames\Collections\DivisionCollection;
use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;

/**
 * Class Country
 * @package MenaraSolutions\FluentGeonames
 */
class Country extends Divisible
{
    use HasTranslations;
    
    /**
     * @var string
     */
    protected $memberClass = State::class;

    /**
     * Country constructor.
     * @param \stdClass $meta
     * @param TranslationRepositoryInterface $translator
     */
    public function __construct(\stdClass $meta, TranslationRepositoryInterface $translator)
    {
        $this->meta = $meta;
        $this->translator = $translator;

        $this->loadMembers();
    }

    /**
     * Get alpha2 ISO code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->meta->iso_3611[0];
    }

    /**
     * Get alpha3 ISO code
     *
     * @return string
     */
    public function getCode3()
    {
        return $this->meta->iso_3611[1];
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->getText($this->meta->names->short) ?: $this->getText($this->meta->names->long);
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return $this->getText($this->meta->names->long) ?: $this->getText($this->meta->names->short);
    }

    /**
     * @param $input
     * @return string
     */
    public function getText($input)
    {
        return $this->translator->translate($input, get_class($this), $this->language);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->getCode(),
            'code_3' => $this->getCode3(),
            'short_name' => $this->getShortName(),
            'long_name' => $this->getLongName()
        ];
    }

    /**
     * @return DivisionCollection
     */
    public function getStates()
    {
        return $this->getMembers();
    }

    /**
     * @return string
     */
    protected function getStoragePath()
    {
        return "resources/states/" . $this->getCode() . ".json";
    }
}