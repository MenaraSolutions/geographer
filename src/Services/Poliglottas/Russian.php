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
    protected $removableLetters = ['я', 'а', 'й', 'ь'];

    /**
     * @var array
     */
    protected $replacementsFrom = [
        'л' => 'а', 'т' => 'а', 'к' => 'а', 'г' => 'а', 'м' => 'а', 'з' => 'а', 'ш' => 'а',
        'р' => 'а', 'с' => 'а', 'д' => 'а', 'н' => 'а', 'й' => 'я', 'я' => 'и', 'а' => 'ы',
        'ь' => 'и', 'в' => 'а', 'п' => 'а', 'ж' => 'а'
    ];

    /**
     * @var array
     */
    protected $replacementsIn = [
        'й' => 'е', 'л' => 'е', 'т' => 'е', 'г' => 'е', 'м' => 'е', 'з' => 'е', 'ш' => 'е',
        'р' => 'е', 'с' => 'е', 'д' => 'е', 'н' => 'е', 'а' => 'е', 'я' => 'е', 'к' => 'е',
        'ь' => 'и', 'в' => 'е', 'п' => 'е', 'ж' => 'е'
    ];

    /**
     * @var array
     */
    protected $vowels = ['а', 'е', 'ё', 'и', 'о', 'у', 'ы', 'э', 'ю', 'я'];

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

        if ($this->isTwoWords($template)) {
            $output = $this->attemptToInflictFirstWordIn($output);
        }

        if (array_key_exists($this->getLastLetter($template), $this->replacementsIn)) {
            $output .= $this->replacementsIn[$this->getLastLetter($template)];
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

	    if ($this->isTwoWords($template)) {
	        $output = $this->attemptToInflictFirstWordFrom($output);
    	}

        if (array_key_exists($this->getLastLetter($template), $this->replacementsFrom)) {
            $output .= $this->replacementsFrom[$this->getLastLetter($template)];
        }

        return $output;
    }

    /**
     * @param $string
     * @return string
     */
    private function attemptToInflictFirstWordIn($string)
    {
        list($first, $second) = explode(' ', $string);

        if (mb_substr($first, mb_strlen($first) - 2) == 'ая') {
            $first = mb_substr($first, 0, mb_strlen($first) - 2) . 'ой';
        }

        if (mb_substr($first, mb_strlen($first) - 2) == 'ий' || mb_substr($first, mb_strlen($first) - 2) == 'ый') {
            $first = mb_substr($first, 0, mb_strlen($first) - 2) . 'ом';
        }

        if (mb_strtolower($first) == 'республика') $first = 'Республике';

        return $first . ' ' . $second;
    }

    /**
     * @param $string
     * @return string
     */
    private function attemptToInflictFirstWordFrom($string)
    {   
	    list($first, $second) = explode(' ', $string);

	    if (mb_substr($first, mb_strlen($first) - 2) == 'ая') {
	        $first = mb_substr($first, 0, mb_strlen($first) - 2) . 'ой';
    	}

        if (mb_substr($first, mb_strlen($first) - 2) == 'ий' || mb_substr($first, mb_strlen($first) - 2) == 'ый') {
            $first = mb_substr($first, 0, mb_strlen($first) - 2) . 'ого';
        }

        if (mb_strtolower($first) == 'республика') $first = 'Республики';

        return $first . ' ' . $second;
    } 

    /**
     * @param $string
     * @return bool
     */
    private function isTwoWords($string)
    {   
	    return mb_substr_count($string, ' ') == 1;
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

    /**
     * @param $form
     * @param string $result
     * @return string
     */
    protected function getPreposition($form, $result = null)
    {
        $preposition = $this->defaultPrepositions[$form];

        if ($result && $form == 'in' && in_array(mb_strtolower(mb_substr($result, 0, 1)), ['в', 'ф']) &&
            ! $this->isVowel(mb_substr($result, 1, 1))) {
            $preposition .= 'о';
        }

        return $preposition;
    }

    /**
     * @param string $character
     * @return bool
     */
    private function isVowel($character)
    {
        return in_array(mb_strtolower($character), $this->vowels);
    }
}
