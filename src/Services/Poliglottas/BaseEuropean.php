<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;

/**
 * Class BaseEuopean
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
abstract class BaseEuropean extends Base
{
    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @param bool $prepositions
     * @return string
     * @throws MisconfigurationException
     */
    public function translate(IdentifiableInterface $subject, $form = 'default', $prepositions = true)
    {
        if (!empty($form) && !method_exists($this, 'inflict' . ucfirst($form))) {
            throw new MisconfigurationException('Language ' . $this->code . ' doesn\'t inflict to ' . $form);
        }

        $meta = $this->fromCache($subject);
        if (!$meta) {
            return false;
        }

        return $this->inflictDefault($meta, $subject->expectsLongNames());
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
     * @param $form
     * @return mixed
     */
    protected function extract(array $meta, $long, $form)
    {
        $field = $long ? 'long' : 'short';
        $backupField = ! $long ? 'long' : 'short';

        return isset($meta[$field][$form]) ? $meta[$field][$form] :
            (isset($meta[$backupField][$form]) ? $meta[$backupField][$form] : false );
    }
}