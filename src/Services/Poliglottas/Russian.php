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
        'subject' => [
            'л' => 'а', 'т' => 'а', 'к' => 'а', 'г' => 'а', 'м' => 'а', 'з' => 'а', 'ш' => 'а',
            'р' => 'а', 'с' => 'а', 'д' => 'а', 'н' => 'а', 'й' => 'я', 'я' => 'и', 'а' => 'ы',
            'ь' => 'и', 'в' => 'а', 'п' => 'а', 'ж' => 'а', 'ф' => 'а', 'х' => 'а'
        ],
        'adjective' => [
            'ая' => 'ой', 'ое' => 'ого', 'ий' => 'ого', 'ый' => 'ого'
        ]
    ];

    /**
     * @var array
     */
    protected $replacementsIn = [
        'subject' => [
            'й' => 'е', 'л' => 'е', 'т' => 'е', 'г' => 'е', 'м' => 'е', 'з' => 'е', 'ш' => 'е',
            'р' => 'е', 'с' => 'е', 'д' => 'е', 'н' => 'е', 'а' => 'е', 'я' => 'и', 'к' => 'е',
            'ь' => 'и', 'в' => 'е', 'п' => 'е', 'ж' => 'е', 'ф' => 'е', 'х' => 'е'
        ],
        'adjective' => [
            'ая' => 'ой', 'ое' => 'ом', 'ий' => 'ом', 'ый' => 'ом'
        ]
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

        if (array_key_exists($this->getLastLetter($template), $this->replacementsIn['subject'])) {
            $output .= $this->replacementsIn['subject'][$this->getLastLetter($template)];
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

        if (array_key_exists($this->getLastLetter($template), $this->replacementsFrom['subject'])) {
            $output .= $this->replacementsFrom['subject'][$this->getLastLetter($template)];
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

        if (array_key_exists($this->getLastLetter($first, 2), $this->replacementsIn['adjective'])) {
            $first = $this->removeLastLetter($first, 2) . $this->replacementsIn['adjective'][$this->getLastLetter($first, 2)];
        }

        if (mb_strtolower($first) == 'республика') $first = 'Республике';
        if (mb_strtolower($first) == 'область') $first = 'Области';
        if (mb_strtolower($first) == 'округ') $first = 'Округе';

        return $first . ' ' . $second;
    }

    /**
     * @param $string
     * @return string
     */
    private function attemptToInflictFirstWordFrom($string)
    {   
	    list($first, $second) = explode(' ', $string);

        if (array_key_exists($this->getLastLetter($first, 2), $this->replacementsFrom['adjective'])) {
            $first = $this->removeLastLetter($first, 2) . $this->replacementsFrom['adjective'][$this->getLastLetter($first, 2)];
        }

        if (mb_strtolower($first) == 'республика') $first = 'Республики';
        if (mb_strtolower($first) == 'область') $first = 'Области';
        if (mb_strtolower($first) == 'округ') $first = 'Округа';

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
     * @param int $count
     * @return string
     */
    private function getLastLetter($string, $count = 1)
    {
        return mb_substr($string, mb_strlen($string) - $count);
    }

    /**
     * @param $string
     * @param int $count
     * @return string
     */
    private function removeLastLetter($string, $count = 1)
    {
        return mb_substr($string, 0, mb_strlen($string) - $count);
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
