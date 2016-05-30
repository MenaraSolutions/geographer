<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\PoliglottaInterface;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;

/**
 * Class Russian
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Russian extends Base
{
    /**
     * @var string
     */
    protected $code = 'ru';

    /**
     * @var array
     */
    protected $removableLetters = ['я', 'а', 'й'];

    /**
     * @var array
     */
    protected $replaceableLettersFrom = [
        'л' => 'а',
        'т' => 'а',
        'к' => 'а',
        'г' => 'а',
        'м' => 'а',
        'з' => 'а',
        'ш' => 'а',
        'р' => 'а',
        'с' => 'а',
        'д' => 'а',
        'н' => 'а',
        'й' => 'я',
        'я' => 'и',
        'а' => 'ы'
    ];

    /**
     * @var array
     */
    protected $replaceableLettersIn = [
        'й' => 'е',
        'л' => 'е',
        'т' => 'е',
        'г' => 'е',
        'м' => 'е',
        'з' => 'е',
        'ш' => 'е',
        'р' => 'е',
        'с' => 'е',
        'д' => 'е',
        'н' => 'е',
        'а' => 'е',
        'я' => 'и'
    ];

   /**
    * @var array
    */
    protected $defaultPrepositions = [
        'from' => 'из',
        'in' => 'в'
    ];

    /**
     * @param $template
     * @return string
     */
    private function removeLastLetterIfNeeded($template)
    {
        if (in_array($this->getLastLetter($template), $this->removableLetters)) {
            return $this->removeLastLetter($template);
        } else {
            return $template;
        }
    }

    /**
     * @param string $template
     * @return string
     */
    protected function inflictIn($template)
    {
        $output = $this->removeLastLetterIfNeeded($template);

        if (array_key_exists($this->getLastLetter($template), $this->replaceableLettersFrom)) {
            $output .= $this->replaceableLettersIn[$this->getLastLetter($template)];
        }

        return $output;
    }

    /**
     * @param string $template
     * @return string
     */
    protected function inflictFrom($template)
    {
        $output = $this->removeLastLetterIfNeeded($template);

        if (array_key_exists($this->getLastLetter($template), $this->replaceableLettersFrom)) {
            $output .= $this->replaceableLettersFrom[$this->getLastLetter($template)];
        }

        return $output;
    }

    /**
     * @param $string
     * @return string
     */
    private function getLastLetter($string)
    {
        return mb_substr($string, mb_strlen($string) - 1);
    }

    /**
     * @param $string
     * @return string
     */
    private function removeLastLetter($string)
    {
        return mb_substr($string, 0, mb_strlen($string) - 1);
    }
}
