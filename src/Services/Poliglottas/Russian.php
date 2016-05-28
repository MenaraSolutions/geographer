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
     * @param IdentifiableInterface $subject
     * @param string $form
     * @param bool $prepositions
     * @return string
     * @throws MisconfigurationException
     */
    public function translate(IdentifiableInterface $subject, $form = 'default', $prepositions = true)
    {
        if (! empty($form) && ! method_exists($this, 'inflict' . ucfirst($form))) {
            throw new MisconfigurationException('Language ' . $this->code . ' doesn\'t inflict to ' . $form);
        }

        $this->loadDictionaries($subject);
        $meta = $this->fromCache($subject);

        $result = $this->{'inflict' . ucfirst($form)}($meta, $subject->expectsLongNames());

        if ($form != 'default' && ! $prepositions) {
            $result = mb_substr($result, mb_strpos($result, ' ') + 1);
        }

        return $result;
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
     * @param array $meta
     * @param $long
     * @return string
     */
    protected function inflictIn(array $meta, $long)
    {
        $form = $this->extract($meta, $long, 'in');

        if (! $form) {
            $form = 'в ' . $this->inflictDefault($meta, $long);

            switch (mb_substr($form, mb_strlen($form) - 1)) {
                case 'й':
                    $form = mb_substr($form, 0, mb_strlen($form) - 1);
                    $form .= 'е';

                    break;

                case 'л':
                    $form .= 'е';

                    break;

                case 'т':
                case 'к':
                case 'г':
                case 'м':
                case 'з':
                case 'ш':
                case 'р':
                case 'с':
                case 'д':
                case 'н':
                    $form .= 'е';

                    break;

                case 'я':
                    $form = mb_substr($form, 0, mb_strlen($form) - 1);
                    $form .= 'и';

                    break;

                case 'а':
                    $form = mb_substr($form, 0, mb_strlen($form) - 1);
                    $form .= 'е';

                    break;

                default:
            }
        }

        return $form;
    }

    /**
     * @param array $meta
     * @param $long
     * @return string
     */
    protected function inflictFrom(array $meta, $long)
    {
        $form = $this->extract($meta, $long, 'from');

        if (! $form) {
            $form = 'из ' . $this->inflictDefault($meta, $long);

            switch (mb_substr($form, mb_strlen($form) - 1)) {
                case 'й':
                    $form = mb_substr($form, 0, mb_strlen($form) - 1);
                    $form .= 'я';

                    break;

                case 'л':
                    $form .= 'а';

                    break;

                case 'т':
                case 'к':
                case 'г':
                case 'м':
                case 'з':
                case 'ш':
                case 'р':
                case 'с':
                case 'д':
                case 'н':
                    $form .= 'а';

                    break;

                case 'я':
                    $form = mb_substr($form, 0, mb_strlen($form) - 1);
                    $form .= 'и';

                    break;

                case 'а':
                    $form = mb_substr($form, 0, mb_strlen($form) - 1);
                    $form .= 'ы';

                    break;

                default:
            }
        }

        return $form;
    }

    /**
     * @param array $meta
     * @param $long
     * @param $form
     * @return mixed
     */
    private function extract(array $meta, $long, $form)
    {
        $field = $long ? 'long' : 'short';
        $backupField = ! $long ? 'long' : 'short';

        return isset($meta[$field][$form]) ? $meta[$field][$form] :
            (isset($meta[$backupField][$form]) ? $meta[$backupField][$form] : false );
    }
}