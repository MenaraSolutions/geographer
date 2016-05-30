<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\PoliglottaInterface;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;

/**
 * Class Russian
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Russian extends Base implements PoliglottaInterface
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
     * @param IdentifiableInterface $subject
     * @param string $form
     * @return string
     * @throws MisconfigurationException
     */
    public function translate(IdentifiableInterface $subject, $form = 'default')
    {
        if (! empty($form) && ! method_exists($this, 'inflict' . ucfirst($form))) {
            throw new MisconfigurationException('Language ' . $this->code . ' doesn\'t inflict to ' . $form);
        }

        $meta = $this->fromCache($subject);
        if (! $meta) return false;
        
        $result = $this->extract($meta, $subject->expectsLongNames(), $form);

        if (! $result) {
            $template = $this->inflictDefault($meta, $subject->expectsLongNames());
            $result = $this->{'inflict' . ucfirst($form)}($template);
        }

        return $result;
    }

    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @return null|string
     */
    public function preposition(IdentifiableInterface $subject, $form)
    {
        $meta = $this->fromCache($subject);
        if (! $meta) return null;

        $result = $this->extract($meta, $subject->expectsLongNames(), $form);
        if ($result) return substr($result, 0, strpos($result, ' '));

        if ($form == 'in') return 'в';
        if ($form == 'from') return 'из';
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

    /**
     * @param array $meta
     * @param bool $long
     * @param string $form
     * @return string|bool
     */
    private function extract(array $meta, $long, $form)
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